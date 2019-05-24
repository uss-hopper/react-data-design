<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use UssHopper\DataDesign\{Like, Profile, Tweet};


/**
 * api for the Tweet class
 *
 * @author Valente Meza <valebmeza@gmail.com>
 **/

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {


	$secrets = new \Secrets("/etc/apache2/capstone-mysql/ddctwitter.ini");
	$pdo = $secrets->getPdoObject();

	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$tweetProfileId = filter_input(INPUT_GET, "tweetProfileId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$tweetContent = filter_input(INPUT_GET, "tweetContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true )) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 402));
	}


	// handle GET request - if id is present, that tweet is returned, otherwise all tweets are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific tweet or all tweets and update reply
		if(empty($id) === false) {
			$reply->data = Tweet::getTweetByTweetId($pdo, $id);
		} else if(empty($tweetProfileId) === false) {
			// if the user is logged in grab all the tweets by that user based  on who is logged in
			$reply->data = Tweet::getTweetByTweetProfileId($pdo, $tweetProfileId);
		} else if(empty($tweetContent) === false) {
			$reply->data = Tweet::getTweetByTweetContent($pdo, $tweetContent)->toArray();
		} else {
			$reply->data = Tweet::getAllTweets($pdo)->toArray();
		}
	} else if($method === "PUT" || $method === "POST") {
		// enforce the user has a XSRF token
		verifyXsrf();

		// enforce the user is signed in
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to post tweets", 401));
		}

		$requestContent = file_get_contents("php://input");
		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestObject = json_decode($requestContent);
		// This Line Then decodes the JSON package and stores that result in $requestObject
		//make sure tweet content is available (required field)
		if(empty($requestObject->tweetContent) === true) {
			throw(new \InvalidArgumentException ("No content for Tweet.", 405));
		}

		// make sure tweet date is accurate (optional field)
		if(empty($requestObject->tweetDate) === true) {
			$requestObject->tweetDate = null;
		}

		//perform the actual put or post
		if($method === "PUT") {

			// retrieve the tweet to update
			$tweet = Tweet::getTweetByTweetId($pdo, $id);
			if($tweet === null) {
				throw(new RuntimeException("Tweet does not exist", 404));
			}

			//enforce the end user has a JWT token


			//enforce the user is signed in and only trying to edit their own tweet
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $tweet->getTweetProfileId()->toString()) {
				throw(new \InvalidArgumentException("You are not allowed to edit this tweet", 403));
			}

			validateJwtHeader();

			// update all attributes
			//$tweet->setTweetDate($requestObject->tweetDate);
			$tweet->setTweetContent($requestObject->tweetContent);
			$tweet->update($pdo);

			// update reply
			$reply->message = "Tweet updated OK";

		} else if($method === "POST") {

			// enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to post tweets", 403));
			}

			//enforce the end user has a JWT token
			validateJwtHeader();

			// create new tweet and insert into the database
			$tweet = new Tweet(generateUuidV4(), $_SESSION["profile"]->getProfileId(), $requestObject->tweetContent, null);
			$tweet->insert($pdo);

			// update reply
			$reply->message = "Tweet created OK";
		}

	} else if($method === "DELETE") {

		//enforce that the end user has a XSRF token.
		verifyXsrf();

		// retrieve the Tweet to be deleted
		$tweet = Tweet::getTweetByTweetId($pdo, $id);
		if($tweet === null) {
			throw(new RuntimeException("Tweet does not exist", 404));
		}

		//enforce the user is signed in and only trying to edit their own tweet
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $tweet->getTweetProfileId()->toString()) {
			throw(new \InvalidArgumentException("You are not allowed to delete this tweet", 403));
		}

		//enforce the end user has a JWT token
		validateJwtHeader();

		// delete tweet
		$tweet->delete($pdo);
		// update reply
		$reply->message = "Tweet deleted OK";
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request", 418));
	}
// update the $reply->status $reply->message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);

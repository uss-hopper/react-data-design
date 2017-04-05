<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DataDesign\{
	Profile,
	Like
};

/**
 * Api for the Like class
 *
 * @author george kephart
 */

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");

	// mock a logged in user by mocking the session and assigning a specific user to it.
	// this is only for testing purposes and should not be in the live code.
	$_SESSION["profile"] = Profile::getProfileByProfileId($pdo, 732);

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	var_dump($method);

	//sanitize the search parameters
	$likeProfileId = filter_input(INPUT_GET, "LikeProfileId", FILTER_VALIDATE_INT);
	$likeTweetId = filter_input(INPUT_GET, "likeTweetId", FILTER_VALIDATE_INT);

	var_dump($likeProfileId);
	var_dump($likeTweetId);

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//gets all likes associated with the end user
		if ($likeProfileId !== null && $likeTweetId !== null) {
			$like = Like::getLikeByLikeTweetIdAndLikeProfileId($pdo, $likeProfileId, $likeTweetId);
			var_dump($like);

			if($like!== null) {
				$reply->data = $like;
			}
			//if none of the search parameters are met throw an exception
		} else if(empty($likeProfileId) === false) {
			$like = Like::getLikeByLikeProfileId($pdo, $likeProfileId)->toArray();
			if($like !== null) {
				$reply->data = $like;
			}
		//get all the likes associated with the tweetId
		} else if(empty($likeTweetId) === false) {
			$like = Like::getLikeByLikeTweetId($pdo, $likeTweetId)->toArray();

			if($like !== null) {
				$reply->data = $like;
			}
		} else {
			throw new InvalidArgumentException("incorrect search parameters ", 404);
		}


	} else if($method === "POST" || $method === "PUT") {

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		if(empty($requestObject->likeProfileId) === true) {
			throw (new \InvalidArgumentException("No Profile linked to the Like", 405));
		}

		if(empty($requestObject->likeTweetId) === true) {
			throw (new \InvalidArgumentException("No tweet linked to the Like", 405));
		}

		if(empty($requestObject->likeDate) === true) {
			$requestObject->LikeDate = null;
		}


		if($method === "POST") {


			// enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in too like posts", 403));
			}

			$like = new Like($requestObject->likeProfileId, $requestObject->likeTweetId, $requestObject->likeDate);
			$like->insert($pdo);
			$reply->message = "liked tweet successful";


		} else if($method === "PUT") {

			//enforce that the end user has a XSRF token.
			verifyXsrf();

			//grab the like by its composite key
			$like = Like::getLikeByLikeTweetIdAndLikeProfileId($pdo, $requestObject->likeProfileId, $requestObject->likeTweetId);
			if($like === null) {
				throw (new RuntimeException("Like does not exist"));
			}

			//enforce the user is signed in and only trying to edit their own like
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $like->getLikeProfileId()) {
				throw(new \InvalidArgumentException("You are not allowed to delete this tweet", 403));
			}

			//preform the actual delete
			$like->delete($pdo);

			//update the message
			$reply->message = "Like successfully deleted";
		}

		// if any other HTTP request is sent throw an exception
	} else {
		throw new \InvalidArgumentException("invalid http request", 400);
	 }
	//catch any exceptions that is thrown and update the reply status and message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);
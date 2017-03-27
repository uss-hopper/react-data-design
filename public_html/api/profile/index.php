<?php


require_once( dirname( __DIR__, 3 ) . "/vendor/autoload.php" );
require_once( dirname( __DIR__, 3 ) . "/php/classes/autoload.php" );
require_once( dirname( __DIR__, 3 ) . "/php/lib/xsrf.php" );
require_once( "/etc/apache2/capstone-mysql/encrypted-config.php" );

use Edu\Cnm\Dmcdonald21\DataDesign\ {
	Tweet,
	Profile
};

/**
 * API for Tweet
 *
 * @author Gkephart
 * @version 1.0
 */

//verify the session, if it is not active start it
if ( session_status() !== PHP_SESSION_ACTIVE ) {
	session_start();
}
//prepare an empty reply
$reply         = new stdClass();
$reply->status = 200;
$reply->data   = null;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$profileAtHandle = filter_input(INPUT_GET, "profileAtHandle", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
	$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	if($method === "GET") {
			//set XSRF cookie
			setXsrfCookie();
			//gets a post by content
			if(empty($id) === false) {
				$profile = Profile::getProfileByProfileId($pdo, $id);
				if($profile !== null) {
					$reply->data = $profile;
				}
			} else if(empty($profileAtHandle) === false) {
				$profile = Profile::getProfileByProfileAtHandle($pdo, $profileAtHandle );
				if($profile !== null) {
					$reply->data = $profile;
				}
			} else if (empty($profileEmail)){
				$profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);
				if($profile !== null) {
					$reply->data = $profile;
				}
			}
	} elseif($method === "PUT" || $method === "POST") {

		//enforce that the XSRF token is present in the header
		verifyXsrf();

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//
		if(empty($requestObject->profileAtHandle) === true) {
			throw(new \InvalidArgumentException ("No Venue ID", 405));
		}

		//profile email is a required field
		if(empty($requestObject->profileEmail) === true) {
			throw(new \InvalidArgumentException ("No post content", 405));
		}

		//profile phone # is a required field
		if(empty($requestObject->profilePhoneNumber) === true) {
			throw(new \InvalidArgumentException ("No post content", 405));
		}
	}



} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch( TypeError $typeError ) {
	$reply->status  = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
header( "Content-type: application/json" );
if ( $reply->data === null ) {
	unset( $reply->data );
}
// encode and return reply to front end caller
echo json_encode( $reply );

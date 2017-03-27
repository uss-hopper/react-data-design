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
	} elseif($method === "PUT") {

		//enforce that the XSRF token is present in the header
		verifyXsrf();

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//profile at handle
		if(empty($requestObject->profileAtHandle) === true) {
			throw(new \InvalidArgumentException ("No Venue ID", 405));
		}

		//profile email is a required field
		if(empty($requestObject->profileEmail) === true) {
			throw(new \InvalidArgumentException ("No profile email present", 405));
		}

		//profile phone # is a required field
		if(empty($requestObject->profilePhoneNumber) === true) {
			throw(new \InvalidArgumentException ("No phone number present", 405));
		}

		//retrieve the profile to be updated
		$profile = Profile::getProfileByProfileId($pdo, $id);
		if($profile === null) {
			throw(new RuntimeException("Profile does not exist", 404));
		}

		$profile->setProfileAtHandle($requestObject->profileAtHAndle);
		$profile->setProfileEmail($requestObject->profileEmail);
		$profile->setProfilePhone($requestObject->profilePhoneNumber);

		/**
		 * update the password if requested
		 * thanks sprout-swap @author:<solomon.leyba@gmail.com>
		 **/
		//enforce that current password new password and confirm password is present
		if(empty($requestObject->currentProfilePassword) === false && empty($requestObject->newProfilePassword) === false && empty($requestContent->profileConfirmPassword) === false) {

			//make sure the new password and confirm password exist
			if($requestObject->newProfilePassword !== $requestObject->profileConfirmPassword) {
				throw(new RuntimeException("New passwords do not match", 401));
			}

			//hash the previous password
			$currentPasswordHash = hash_pbkdf2("sha512", $requestObject->currentProfilePassword, $profile->getProfileSalt(), 262144);

			//make sure the hash given by the end user matches what is in the database
			if($currentPasswordHash !== $profile->getProfilePasswordHash()) {
				throw(new \RuntimeException("Old password is incorrect", 401));
			}

			// salt and hash the new password and update the profile object
			$newPasswordSalt = bin2hex(random_bytes(16));
			$newPasswordHash = hash_pbkdf2("sha512", $requestObject->newProfilePassword, $newPasswordSalt, 262144);
			$profile->setProfilePasswordHash($newPasswordHash);
			$profile->setProfileSalt($newPasswordSalt);
		}

		//preform the actual update to the database
		$profile->update($pdo);

	} elseif($method === "DELETE") {

		//verify the XSRF Token
		verifyXsrf();

		$profile = Profile::getProfileByProfileId($pdo, $id);
		if($profile === null) {
			throw (new RuntimeException("Profile does not exist"));
		}

		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $id) {
			throw(new \InvalidArgumentException("You are not allowed to access this profile", 405));
		}

		//delete the post from the database
		$profile->delete($pdo);
	} else {
		throw (new InvalidArgumentException("Invalid HTTP request", 400));
	}
}
// catch any exceptions that were thrown and update the status and message state variable fields
catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();

} catch( TypeError $typeError ) {
	$reply->status  = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

// encode and return reply to front end caller
header( "Content-type: application/json" );
if ( $reply->data === null ) {
	unset( $reply->data );
}
// encode and return reply to front end caller
echo json_encode( $reply );

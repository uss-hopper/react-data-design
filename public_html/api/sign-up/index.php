<?php
require_once dirname( __DIR__, 3 ) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\DataDesign\Profile;

/**
 * api for signing up too DDC Twitter
 *
 * @author Gkephart <GKephart@cnm.edu>
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
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");
	//determine which HTTP method was used

	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	if($method === "POST") {

		//decode the json and turn it into a php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//verify that all needed fields are present
		if(empty($requestObject->profilePassword) === true) {
			throw(new \InvalidArgumentException ("Must input valid password", 405));
		}

		//profile at handle is a required field
		if(empty($requestObject->profileAtHandle) === true) {
			throw(new \InvalidArgumentException ("No profile @handle", 405));
		}

		//profile email is a required field
		if(empty($requestObject->profileEmail) === true) {
			throw(new \InvalidArgumentException ("No profile email present", 405));
		}

		$salt = bin2hex(random_bytes(32));
		$hash = hash_pbkdf2("sha512", $requestObject->profilePassword, $salt, 262144);

		$profileActivationToken = bin2hex(random_bytes(16));

		//create the profile object and prepare to insert into the database
		$profile = new Profile(null, $profileActivationToken, $requestObject->profileAtHandle, $requestObject->profileEmail, $hash, $requestObject->profilePhone, $salt);

		//insert the profile into the database
		$profile->insert($pdo);

		//compose the email message to send with th activation token
		$messageSubject = "One step closer to Sticky Head -- Account Activation";

		//building the activation link that can travel to another server and still work. This is the link that will be clicked to confirm the account.
		//make sure URL is /public_html/api/activation/$activation
		$basePath = dirname($_SERVER["SCRIPT_NAME"], 3);

		//create the path
		$urlglue = $basePath . "/activation/" . $profileActivationToken;

		//create the redirect link
		$confirmLink = "https://" . $_SERVER["SERVER_NAME"] . $urlglue;

		//compose message to send with email
		$message = <<< EOF
<h2>Welcome to DDCTwitter.</h2>
<p>In order to start posting tweets of cats you must confirm your account </p>
<p><a href="$confirmLink">$confirmLink</a></p>
EOF;
		$response = sendEmail($requestObject->profileEmail, $requestObject->profilePhoneNumber, $messageSubject, $message);
		if($response === "Email sent.") {
			$reply->message = "Sign up was successful, please check your email for activation message.";
		} else {
			throw(new InvalidArgumentException("Error sending email."));
		}
	} else{
		throw (new InvalidArgumentException("invalid http request"));
	}

}catch(\Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(\TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);

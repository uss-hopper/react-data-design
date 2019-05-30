
<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");


use Edu\Cnm\DataDesign\Profile;
/**
 * API to check profile activation status
 * @author Gkephart
 */
// Check the session. If it is not active, start the session.
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try{
	// grab the MySQL connection

	$secrets = new \Secrets("/etc/apache2/capstone-mysql/ddctwitter.ini");
	$pdo = $secrets->getPdoObject();


	//check the HTTP method being used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input (never trust the end user
	$activation = filter_input(INPUT_GET, "activation", FILTER_SANITIZE_STRING);

	// make sure the activation token is the correct size
	if(strlen($activation) !== 32){
		throw(new InvalidArgumentException("activation has an incorrect length", 405));
	}

	// verify that the activation token is a string value of a hexadeciaml
	if(ctype_xdigit($activation) === false) {
		throw (new \InvalidArgumentException("activation is empty or has invalid contents", 405));
	}
	// handle The GET HTTP request
	if($method === "GET"){

		// set XSRF Cookie
		setXsrfCookie();

		//find profile associated with the activation token
		$profile = Profile::getProfileByProfileActivationToken($pdo, $activation);

		//verify the profile is not null
		if($profile !== null){

			//make sure the activation token matches
			if($activation === $profile->getProfileActivationToken()) {

				//set activation to null
				$profile->setProfileActivationToken(null);

				//update the profile in the database
				$profile->update($pdo);

				//set the reply for the end user
				$reply->data = "Thank you for activating your account, you will be auto-redirected to your profile shortly.";
			}
		} else {
			//throw an exception if the activation token does not exist
			throw(new RuntimeException("Profile with this activation code does not exist", 404));
		}
	} else {
		//throw an exception if the HTTP request is not a GET
		throw(new InvalidArgumentException("Invalid HTTP method request", 403));
	}

	//update the reply objects status and message state variables if an exception or type exception was thrown;
} catch (Exception $exception){
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError){
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

//prepare and send the reply
header("Content-type: application/json");
if($reply->data === null){
	unset($reply->data);
}
echo json_encode($reply);
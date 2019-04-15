<?php

require_once dirname(__DIR__, 1) . "/vendor/autoload.php";
require_once dirname(__DIR__) . "/lib/uuid.php";

use Lcobucci\JWT\{
	Builder, Signer\Hmac\Sha512, Parser, ValidationData
};

/**
 * this method creates a JWT that will be used on the front end to authenticate users, activate protected routes, and verification of who is logged in.
 * this token is viewable by anyone and SHOULD NOT contain any sensitive information about the user.
 *
 * @see https://github.com/lcobucci/jwt/blob/3.2/README.md documentation for the composer package used for JWT
 * @param string $value name of the custom object that will be used for validation.
 * @param stdClass $content the actual object that will be used for authentication on the front end
 */

function setJwtAndAuthHeader(string $value, stdClass $content): void {

//enforce that the session is active
	if(session_status() !== PHP_SESSION_ACTIVE) {
		throw(new RuntimeException("session not active"));
	}

// create the signer object
	$signer = new Sha512();

//create a UUID to sign the JWT and then store it in the session
	$signature = generateUuidV4();

	//store the signature in string format
	$_SESSION["signature"] = $signature->toString();

	$token = (new Builder())
		->set($value, $content)
		->setIssuer("https://bootcamp-coders.cnm.edu")
		->setAudience("https://bootcamp-coders.cnm.edu")
		->setId(session_id())
		->setIssuedAt(time())
		->setExpiration(time() + 3600)
		->sign($signer, $signature->toString())
		->getToken();

		$_SESSION["JWT-TOKEN"] = (string)$token;

	// add the JWT to the header
	header("X-JWT-TOKEN: $token");
}

/**
 * verifies the X-JWT-TOKEN sent by Angular matches the JWT-TOKEN saved in this session.
 * this function uses two custom methods to insure that the JWT-TOKENs match
 * This function returns nothing, but will throw an exception when something does not match
 */
function validateVerifyJwt() {

	// retrieve the jwt from the header
	$headerJwt = validateJwtHeader();


	//enforce that the JWT is Valid and verified.
	verifiedAndValidatedSignature($headerJwt);

}

/**
 * this method enforces that the session contains all necessary information and that the JWT in the session matches the
 * JWT sent by angular
 *
 * @return \Lcobucci\JWT\Token the JWT token supplied by angular in the header
 */
function validateJwtHeader () : \Lcobucci\JWT\Token   {
	//if  the JWT does not exist in the cookie jar throw an exception
	$headers = array_change_key_case(apache_request_headers(), CASE_UPPER);

	if(array_key_exists("X-JWT-TOKEN", $headers) === false) {
		throw new InvalidArgumentException("invalid JWT token", 418);
	}

	//enforce the session has needed content
	if(empty( $_SESSION["signature"]) === true ) {
		throw new InvalidArgumentException("not logged in", 401);
	}

	//grab the string representation of the Token from the header then parse it into an object
	$headerJwt = $headers["X-JWT-TOKEN"];


	$headerJwt = (new Parser())->parse($headerJwt);


	//enforce that the JWT payload in the session matches the payload from header
	if ($_SESSION["JWT-TOKEN"] !== (string)$headerJwt) {
		$_COOKIE = [];
		$_SESSION = [];
		throw (new InvalidArgumentException("please log in again", 400));
	}

	return $headerJwt;
}


/**
 * this method uses built in methods from the composer package to enforce that the jwt has not been tampered with and is not expired.
 *
 * @param \Lcobucci\JWT\Token $headerJwt X-JWT-TOKEN sent by Angular
 */

function verifiedAndValidatedSignature ( \Lcobucci\JWT\Token  $headerJwt) : void {

	//enforce the JWT is valid
	$validator = new ValidationData();
	$validator->setId(session_id());
	if($headerJwt->validate($validator) !== true) {
		throw (new InvalidArgumentException("not authorized to perform task", 402));
	}

	//verify that the JWT was signed by the server
	$signer = new Sha512();

	if($headerJwt->verify($signer, $_SESSION["signature"]) !== true) {
		throw (new InvalidArgumentException("not authorized to perform task", 403));
	}
}


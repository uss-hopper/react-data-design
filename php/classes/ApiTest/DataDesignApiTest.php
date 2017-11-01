<?php

namespace Edu\Cnm\DataDesign\ApiTest;

use Edu\Cnm\DataDesign\Profile;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJarInterface;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Cookie;
use Zend\Code\Reflection\ParameterReflection;

require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


abstract class DataDesignApiTest extends TestCase {

	/**
	 * cookie jar for guzzle
	 * @var CookieJarInterface $cookieJar
	 **/
	protected $cookieJar = null;
	/**
	 * guzzle HTTP client
	 * @var Client $guzzle
	 **/
	protected $guzzle = null;
	/**
	 * XSRF token for non-GET requests
	 * @var  $xsrfToken
	 **/
	protected $xsrfToken = "";

	/**
	 * JWT token used for authentication
	 * @var string $jwtToken
	 */
	protected $jwtToken = "";

	/**
	 * the password used to create the test profile
	 * @var string $testProfilePassword
	 */
	protected $testProfilePassword = "password";

	/**
	 * sign in the mocked user to make testing stream lined and easy
	 *
	 * @param Profile $profile profile to be logged in\
	 *
	 */
	public function signIn(Profile $profile) : void {


		$requestObject = (object) ["profileEmail" => $profile->getProfileEmail(), "profilePassword" => $this->testProfilePassword];

		//var_dump($requestObject);

		$this->guzzle->get("https://bootcamp-coders.cnm.edu");


		$this->guzzle->post(
			"https://bootcamp-coders.cnm.edu/~gkephart/ddc-twitter/public_html/api/sign-in/",
			["body" => json_encode($requestObject), "headers" => ["X-XSRF-TOKEN" => $this->xsrfToken->getValue()]]);

		//grab the (jwt) cookie from the cookieJar and save it for later
		$this->jwtToken = $this->cookieJar->getCookieByName("JWT-TOKEN");

		var_dump($this->jwtToken);


	}

	/**
	 * setup method for testing my implementation of JWT.
	 *
	 */
	public function setCookies() {


		// get an XSRF token by visiting the main site
		$this->guzzle = new Client(["cookies" => true]);
		$this->guzzle->get("https://bootcamp-coders.cnm.edu/");

		//put the cookies into the cookie jar
		$this->cookieJar = $this->guzzle->getConfig("cookies");

		//grab the (xsrf) cookie from  the cookie jar to eat a little know then the rest later
		$this->xsrfToken = $this->cookieJar->getCookieByName("XSRF-TOKEN");

	}

	/**
	 * tear down method to end the session
	 * @param Profile $profile profile that needs to be deleted.
	 * @param \PDO $pdo database connection object
	 */
	public function logoutForTearDown(Profile $profile, \PDO $pdo) {
		$this->guzzle->get("https://bootcamp-coders.cnm.edu/~gkephart/ng4-bootcamp/public_html/api/sign-out/");

		// delete the test profile to keep to keep tests dry
		$profile->delete($pdo);
	}


	public function getPdoObject() : \PDO {
		return connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");
	}
}

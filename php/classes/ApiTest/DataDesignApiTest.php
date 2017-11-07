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
	 * @var  $jwtToken
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
}

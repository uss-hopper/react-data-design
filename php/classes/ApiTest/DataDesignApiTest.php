<?php

namespace Edu\Cnm\DataDesign\ApiTest;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJarInterface;
use PHPUnit\Framework\TestCase;

require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

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
	 * @var string $xsrfToken
	 **/
	protected $xsrfToken = "";

	/**
	 * JWT token used for authentication
	 * @var string JWT
	 */
	protected $jwtToken = "";

	public function signIn() {

		$requestObject = (object) ["profileEmail" => "email@email.com", "profilePassword" => "password"];

		$this->guzzle->get("https://bootcamp-coders.cnm.edu");


		$reply = $this->guzzle->post(
			"https://bootcamp-coders.cnm.edu/~gkephart/ng4-bootcamp/public_html/api/sign-in/",
			["body" => json_encode($requestObject), "headers" => ["X-XSRF-TOKEN" => $this->xsrfToken->getValue()]]);



		$replyObject = json_decode($reply->getBody());


		//enforce that the ajax call was successful and the headers are returned successfully
		$this->assertEquals($reply->getStatusCode(), 200);
		$this->assertEquals($replyObject->status, 200);



	}

	/**
	 * setup method for testing my implementation of JWT.
	 */
	public function setUp() {

		// get an XSRF token by visiting the main site
		$this->guzzle = new Client(["cookies" => true]);
		$this->guzzle->get("https://bootcamp-coders.cnm.edu/");

		//put the cookies into the cookie jar
		$this->cookieJar = $this->guzzle->getConfig("cookies");

		//grab the (xsrf) cookie from  the cookie jar to eat a little know then the rest later
		$this->xsrfToken = $this->cookieJar->getCookieByName("XSRF-TOKEN");


		// sign in to get a JWT token
		$this->signIn();

		//grab the (jwt) cookie from the cookieJar and save it for later
		$this->jwtToken = $this->cookieJar->getCookieByName("JWT-TOKEN");




	}

	/**
	 * tear down method to end the session
	 */
	public final function tearDown() {
		$this->guzzle->get("https://bootcamp-coders.cnm.edu/~gkephart/ng4-bootcamp/public_html/api/sign-out/");
	}
}

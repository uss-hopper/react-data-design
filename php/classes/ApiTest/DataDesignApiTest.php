<?php

namespace Edu\Cnm\DataDesign\ApiTest;

use Edu\Cnm\DataDesign\Profile;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJarInterface;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Cookie;

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
	 * a fake profile that will be used to own the test
	 * @var Profile $testProfile
	 */
	protected $testProfile = null;

	/**
	 * a pdo Object to help with mundane database procedures
	 * @var \PDO $pdo
	 */
	protected $pdo = null;

	/**
	 * the password used to create the test profile
	 * @var string $testProfilePassword
	 */
	protected $testProfilePassword = "password";


	/**
	 * create a mock profile to make testing stream lined and easy.
	 * TODO: I might have to pass the pdo object as parameter into the method
	 */

	public function createProfile() : void {

		// create a valid salt and hash to create a valid profile object
		$salt = bin2hex(random_bytes(32));
		$hash = hash_pbkdf2("sha12", $this->testProfilePassword, $salt, 262144 );

		$this->testProfile = new Profile(generateUuidV4(), null, "@athandle", "email@email.com", $hash, "505-867-5309",$salt);
		$this->testProfile->insert($this->pdo);
	}


	/**
	 * sign in the mocked user to make testing stream lined and easy
	 * TODO: I might have to manage the scope of profileEmail in a more forceful way.
	 */
	public function signIn() {

		$requestObject = (object) ["profileEmail" => $this->testProfile->getProfileEmail(), "profilePassword" => $this->testProfilePassword];

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

	// create the connection to the database.
		$this->pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");

		$this->createProfile();


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

		// delete the test profile to keep to keep tests dry
		$this->testProfile->delete($this->pdo);
	}
}

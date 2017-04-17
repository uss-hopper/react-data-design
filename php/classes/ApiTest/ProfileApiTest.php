<?php

namespace Edu\Cnm\DataDesign\ApiTest;

use Edu\Cnm\DataDesign\Profile;

require_once(dirname(__DIR__) . "/autoload.php");

/**
 * Profile API test
 *
 * ensures the user can edit their own profile and delete.
 * @package Edu\Cnm\DataDesign\ApiTest
 */
class ProfileApiTest extends DataDesignApiTest {

	/**
	 * API endpoint to connect too
	 */
	protected $apiEndPoint = "https://bootcamp-coders.cnm.edu/~gkephart/data-design/public_html/api/profile/";
	protected $salt = null;
	protected $hash = null;
	protected $profile = null;
	protected $profileActivationToken = null;

	/**
	 * create a profile object to insert into the database to use with testing
	 */

	ublic function setUp() {
		parent::setUp();
		//connect to the database in order to stamp out needed object dependencies
		$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");

		//create a hash and salt too own the test
		$password = "thisIsNotMyPassword";
		$salt = bin2hex(random_bytes(32));
		$hash = hash_pbkdf2("sha512", $password, $salt, 262144);

		$profile = new Profile(null, null,"phpunit","gkephart@gmail", $hash, "5050001111", $salt);
		$profile->insert($pdo);
	}


	/**
	 * helper method to create a valid  object to send as a request object
	 *
	 * @return \stdClass valid object
	 */

	public function createValidObject() : \stdClass{
		$requestObject = new \stdClass();
		$requestObject->atHandle = "phpunit";
		$requestObject->profileEmail = "g.e.kephart@gmail.com";
		$requestObject->profilePhone ="505-709-8165";
		$requestObject->password = "newPass";
		$requestObject->profileConfirmPassword = "newPass";
		return($requestObject);
	}



}
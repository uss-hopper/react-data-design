<?php

namespace Edu\Cnm\DataDesign\ApiTest;

require_once(dirname(__DIR__) . "/autoload.php");

/**
 * Sign Up API Test
 *
 * ensures the sign up
 *
 * @package Edu\Cnm\DataDesign\ApiTest
 **/
class SignUpApiTest extends DataDesignApiTest {
	/**
	 * API endpoint to connect to
	 * @var string $apiEndpoint
	 **/
	protected $apiEndpoint = "https://bootcamp-coders.cnm.edu/~dmcdonald21/data-design/public_html/api/sign-up/";

	/**
	 * helper method to create a valid object to send to the API
	 *
	 * @return \stdClass valid object created
	 **/
	public function createValidObject() : \stdClass {
		$requestObject = new \stdClass();
		$requestObject->profileAtHandle = "phpunit";
		$requestObject->profileEmail = "dmcdonald21@cnm.edu";
		$requestObject->profilePassword = "thisIsNotMyPassword";
		$requestObject->profilePasswordConfirm = "thisIsNotMyPassword";
		$requestObject->profilePhone = "+12125551212";
		return($requestObject);
	}

	/**
	 * tests signing up with valid data
	 **/
	public function testValidSignUp() {
		$requestObject = $this->createValidObject();
		$this->assertNotEmpty($this->xsrfToken);
		$reply = $this->guzzle->post($this->apiEndpoint, ["body" => json_encode($requestObject), "headers" => ["X-XSRF-TOKEN" => $this->xsrfToken]]);
		$this->assertEquals($reply->getStatusCode(), 200);
		$replyObject = json_decode($reply->getBody());
		$this->assertEquals($replyObject->status, 200);
	}
}
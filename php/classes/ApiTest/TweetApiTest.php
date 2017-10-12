<?php
namespace Edu\Cnm\DataDesign\ApiTest;
use Edu\Cnm\DataDesign\Tweet;

require_once(dirname(__DIR__) . "/autoload.php");
/**
 * Test to insure that the new JWT implementation is bug free and efficient cross checking against the Tweet Api
 *
 *
 */
class TweetApiTest extends DataDesignApiTest {
	/**
	 * Api endpoint to test against
	* @var string $postApiEndPoint
	 */
	protected $postApiEndPoint = "https://bootcamp-coders.cnm.edu/~gkephart/ng4-bootcamp/public_html/api/tweet/";

	/**
	 * tweet object to use to validate that the API is returning tweets correctly
	 * @var Tweet $testTweet
	 */
	protected $testTweet = null;

	/**
	 * tweet object that will be used to help test Post Put and Delete.
	 * @var \stdClass $newTweet
	 */
	protected $newTweet = null;


	/**
	 * create dependent object before running each test
	 */
	public final function setUp() : void {
		parent::setUp();

		$this->newTweet = (object) ["tweetContent" => bin2hex(random_bytes(12))];

		// create needed DateTime for testTweet
		$tweetDate = new \DateTime();

		// create the actual object to use for testing
		$this->testTweet = new Tweet(generateUuidV4(), $this->testProfile->getProfileId(), bin2hex(random_bytes(12)), $tweetDate);
		$this->testTweet->insert($this->pdo);



	}





	/**
	 * @test method to test get tweetByTweetId this will run through all of the test case for validateJwtToken.
	 *
	 */
	public function validGetTweetByTweetId() : void {
		//make a ajax call to the restEndpoint in order  to get a tweet by tweetId
		$reply = $this->guzzle->get($this->postApiEndPoint . "35", ["headers" =>
				["X-XSRF-TOKEN" => $this->xsrfToken->getValue(), "X-JWT-TOKEN" => $this->jwtToken->getValue()]]
		);
		//decode the reply object for later use
		$replyObject = json_decode($reply->getBody());
		//enforce that the ajax call was successful and the headers are returned successfully
		$this->assertEquals($reply->getStatusCode(), 200);
		$this->assertEquals($replyObject->status, 200);
	}
	/**
	 * @test invalid test for grabbing a tweet by tweet Id using an incorrect JWT
	 *
	 */
	public function invalidGetTweetByTweetId () : void  {
		$invalidJwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJwcm9maWxlIjp7InByb2ZpbGVJZCI6NjcsInByb2ZpbGVBdEhhbmRsZSI6ImhlbGxvIn0sImlzcyI6Imh0dHBzOlwvXC9ib290Y2FtcC1jb2RlcnMuY25tLmVkdSIsImF1ZCI6Imh0dHBzOlwvXC9ib290Y2FtcC1jb2RlcnMuY25tLmVkdSIsImp0aSI6ImtqMjZjYjRvcmN1c2ZhaGFiYzRxa3VwMXQ4IiwiaWF0IjoxNTA2ODk4ODM1LCJleHAiOjE1MDY5MDI0MzV9.RSQrkBpoeQhR8FRmoU7-gd-UdHapH0ifKgU2rE-3ALSbGjoI4H4-hSUFu3Cnc5lHuf2zfKhS7bgWcW-MsbGcLQ";
		//make a ajax call to the restEndpoint in order  to get a tweet by tweetId
		$reply = $this->guzzle->get($this->postApiEndPoint . "35", ["headers" =>
				["X-XSRF-TOKEN" => $this->xsrfToken->getValue(), "X-JWT-TOKEN" => $invalidJwt]]
		);
		//decode the reply object for later use
		$replyObject = json_decode($reply->getBody());
		//enforce that the correct error is thrown
		$this->assertEquals($reply->getStatusCode(), 200);
		$this->assertEquals($replyObject->status, 400);
	}
	/**
	 * @test invalid test to enforce that protected info cannot be accessed without a jwt
	 */
	public function invalidGetTweetWhenLoggedOut() {
		// kill the session to verify safe checks work
		$this->guzzle->get("https://bootcamp-coders.cnm.edu/~gkephart/ng4-bootcamp/public_html/api/sign-out/");
		//make a ajax call to the restEndpoint in order  to get a tweet by tweetId
		$reply = $this->guzzle->get($this->postApiEndPoint . "35",
			["headers" => ["X-XSRF-TOKEN" => $this->xsrfToken->getValue(),  "X-JWT-TOKEN" => $this->jwtToken->getValue()]]);
		//decode the reply object for later use
		$replyObject = json_decode($reply->getBody());
		//enforce that the correct error is thrown
		$this->assertEquals($reply->getStatusCode(), 200);
		$this->assertEquals($replyObject->status, 400);
	}
}
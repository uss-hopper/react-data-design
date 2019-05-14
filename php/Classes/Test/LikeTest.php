<?php

namespace UssHopper\DataDesign\Test;
use UssHopper\DataDesign\{
	Profile, Tweet, Image, Like
};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Like class
 *
 * This is a complete PHPUnit test of the Like class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Like
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class LikeTest extends DataDesignTest {


	/**
	 * Profile that created the liked the Tweet; this is for foreign key relations
	 * @var  Profile $profile
	 **/
	protected $profile;

	/**
	 * Tweet that was liked; this is for foreign key relations
	 * @var Tweet $tweet
	 **/
	protected $tweet;

	/**
	 * valid hash to use
	 * @var $VALID_HASH
	 */
	protected $VALID_HASH;

	/**
	 * timestamp of the Like; this starts as null and is assigned later
	 * @var \DateTime $VALID_LIKEDATE
	 **/
	protected $VALID_LIKEDATE;

	/**
	 * valid activationToken to create the profile object to own the test
	 * @var string $VALID_ACTIVATION
	 */
	protected $VALID_ACTIVATION;




	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() : void {
		// run the default setUp() method first
		parent::setUp();

		// create a salt and hash for the mocked profile
		$password = "abc123";
		$this->VALID_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));

		// create and insert the mocked profile
		$this->profile = new Profile(generateUuidV4(), null,"@phpunit", "https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif", "test@phpunit.de",$this->VALID_HASH, "+12125551212");
		$this->profile->insert($this->getPDO());

		// create the and insert the mocked tweet
		$this->tweet = new Tweet(generateUuidV4(), $this->profile->getProfileId(), "PHPUnit like test passing");
		$this->tweet->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_LIKEDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Like and verify that the actual mySQL data matches
	 **/
	public function testInsertValidLike() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("like");

		// create a new Like and insert to into mySQL
		$like = new Like($this->profile->getProfileId(), $this->tweet->getTweetId(), $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoLike = Like::getLikeByLikeTweetIdAndLikeProfileId($this->getPDO(), $this->profile->getProfileId(), $this->tweet->getTweetId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getLikeTweetId(), $this->tweet->getTweetId());
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoLike->getLikeDate()->getTimeStamp(), $this->VALID_LIKEDATE->getTimestamp());
	}
	/**
	 * test creating a Like and then deleting it
	 **/
	public function testDeleteValidLike() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("like");

		// create a new Like and insert to into mySQL
		$like = new Like($this->profile->getProfileId(), $this->tweet->getTweetId(), $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());

		// delete the Like from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
		$like->delete($this->getPDO());

		// grab the data from mySQL and enforce the Tweet does not exist
		$pdoLike = Like::getLikeByLikeTweetIdAndLikeProfileId($this->getPDO(), $this->profile->getProfileId(), $this->tweet->getTweetId());
		$this->assertNull($pdoLike);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("like"));
	}

	/**
	 * test inserting a Like and regrabbing it from mySQL
	 **/
	public function testGetValidLikeByTweetIdAndProfileId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("like");

		// create a new Like and insert to into mySQL
		$like = new Like($this->profile->getProfileId(), $this->tweet->getTweetId(), $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoLike = Like::getLikeByLikeTweetIdAndLikeProfileId($this->getPDO(), $this->profile->getProfileId(), $this->tweet->getTweetId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getLikeTweetId(), $this->tweet->getTweetId());

		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoLike->getLikeDate()->getTimeStamp(), $this->VALID_LIKEDATE->getTimestamp());
	}

	/**
	 * test grabbing a Like that does not exist
	 **/
	public function testGetInvalidLikeByTweetIdAndProfileId() {
		// grab a tweet id and profile id that exceeds the maximum allowable tweet id and profile id
		$like = Like::getLikeByLikeTweetIdAndLikeProfileId($this->getPDO(), generateUuidV4(), generateUuidV4());
		$this->assertNull($like);
	}

	/**
	 * test grabbing a Like by tweet id
	 **/
	public function testGetValidLikeByTweetId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("like");

		// create a new Like and insert to into mySQL
		$like = new Like($this->profile->getProfileId(), $this->tweet->getTweetId(), $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Like::getLikeByLikeTweetId($this->getPDO(), $this->tweet->getTweetId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DataDesign\\Like", $results);

		// grab the result from the array and validate it
		$pdoLike = $results[0];
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getLikeTweetId(), $this->tweet->getTweetId());

		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoLike->getLikeDate()->getTimeStamp(), $this->VALID_LIKEDATE->getTimestamp());
	}

	/**
	 * test grabbing a Like by a tweet id that does not exist
	 **/
	public function testGetInvalidLikeByTweetId() : void {
		// grab a tweet id that exceeds the maximum allowable tweet id
		$like = Like::getLikeByLikeTweetId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $like);
	}

	/**
	 * test grabbing a Like by profile id
	 **/
	public function testGetValidLikeByProfileId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("like");

		// create a new Like and insert to into mySQL
		$like = new Like($this->profile->getProfileId(), $this->tweet->getTweetId(), $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Like::getLikeByLikeProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
		$this->assertCount(1, $results);

		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DataDesign\\Like", $results);

		// grab the result from the array and validate it
		$pdoLike = $results[0];
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getLikeTweetId(), $this->tweet->getTweetId());

		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoLike->getLikeDate()->getTimeStamp(), $this->VALID_LIKEDATE->getTimestamp());
	}

	/**
	 * test grabbing a Like by a profile id that does not exist
	 **/
	public function testGetInvalidLikeByProfileId() : void {
		// grab a tweet id that exceeds the maximum allowable profile id
		$like = Like::getLikeByLikeProfileId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $like);
	}
}
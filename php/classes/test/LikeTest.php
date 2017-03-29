<?php
namespace Edu\Cnm\DataDesign\Test;

use Edu\Cnm\DataDesign\{Like, Profile, Tweet};

// grab the project test parameters
require_once("DataDesignTest.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

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
	 * timestamp of the Like; this starts as null and is assigned later
	 * @var \DateTime $VALID_LIKEDATE
	 **/
	protected $VALID_LIKEDATE = null;
	/**
	 * Profile that created the liked the Tweet; this is for foreign key relations
	 * @var \Edu\Cnm\Dmcdonald21\DataDesign\Profile $profile
	 **/
	protected $profile = null;
	/**
	 * Tweet that was liked; this is for foreign key relations
	 * @var \Edu\Cnm\Dmcdonald21\DataDesign\Tweet $tweet
	 **/
	protected $tweet = null;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() {
		// run the default setUp() method first
		parent::setUp();

		// create and insert a Profile to own the test Tweet
		$this->profile = new Profile(null, "@phpunit", "test@phpunit.de", "+12125551212");
		$this->profile->insert($this->getPDO());

		// create the test Tweet
		$this->tweet = new Tweet(null, $this->profile->getProfileId(), "PHPUnit like test passing");
		$this->tweet->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_LIKEDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Like and verify that the actual mySQL data matches
	 **/
	public function testInsertValidLike() {
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
		$this->assertEquals($pdoLike->getLikeDate(), $this->VALID_LIKEDATE);
	}

	/**
	 * test creating Like that makes no sense
	 *
	 * @expectedException \TypeError
	 **/
	public function testInsertInvalidLike() {
		// create a like without foreign keys and watch it fail
		$like = new like(null, null, null);
	}

	/**
	 * test creating a Like and then deleting it
	 **/
	public function testDeleteValidLike() {
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
	public function testGetValidLikeByTweetIdAndProfileId() {
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
		$this->assertEquals($pdoLike->getLikeDate(), $this->VALID_LIKEDATE);
	}

	/**
	 * test grabbing a Like that does not exist
	 **/
	public function testGetInvalidLikeByTweetIdAndProfileId() {
		// grab a tweet id and profile id that exceeds the maximum allowable tweet id and profile id
		$like = Like::getLikeByLikeTweetIdAndLikeProfileId($this->getPDO(), DataDesignTest::INVALID_KEY, DataDesignTest::INVALID_KEY);
		$this->assertNull($like);
	}

	/**
	 * test grabbing a Like by tweet id
	 **/
	public function testGetValidLikeByTweetId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("like");

		// create a new Like and insert to into mySQL
		$like = new Like($this->profile->getProfileId(), $this->tweet->getTweetId(), $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Like::getLikeByLikeTweetId($this->getPDO(), $this->tweet->getTweetId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Dmcdonald21\\DataDesign\\Like", $results);

		// grab the result from the array and validate it
		$pdoLike = $results[0];
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getLikeTweetId(), $this->tweet->getTweetId());
		$this->assertEquals($pdoLike->getLikeDate(), $this->VALID_LIKEDATE);
	}

	/**
	 * test grabbing a Like by a tweet id that does not exist
	 **/
	public function testGetInvalidLikeByTweetId() {
		// grab a tweet id that exceeds the maximum allowable tweet id
		$like = Like::getLikeByLikeTweetId($this->getPDO(), DataDesignTest::INVALID_KEY);
		$this->assertCount(0, $like);
	}

	/**
	 * test grabbing a Like by profile id
	 **/
	public function testGetValidLikeByProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("like");

		// create a new Like and insert to into mySQL
		$like = new Like($this->profile->getProfileId(), $this->tweet->getTweetId(), $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Like::getLikeByLikeProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Dmcdonald21\\DataDesign\\Like", $results);

		// grab the result from the array and validate it
		$pdoLike = $results[0];
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getLikeTweetId(), $this->tweet->getTweetId());
		$this->assertEquals($pdoLike->getLikeDate(), $this->VALID_LIKEDATE);
	}

	/**
	 * test grabbing a Like by a profile id that does not exist
	 **/
	public function testGetInvalidLikeByProfileId() {
		// grab a tweet id that exceeds the maximum allowable profile id
		$like = Like::getLikeByLikeProfileId($this->getPDO(), DataDesignTest::INVALID_KEY);
		$this->assertCount(0, $like);
	}
}
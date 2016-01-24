<?php
namespace Edu\Cnm\Dmcdonald21\DataDesign\Test;

use Edu\Cnm\Dmcdonald21\DataDesign\{Favorite, Profile, Tweet};

// grab the project test parameters
require_once("DataDesignTest.php");

// grab the class under scrutiny
require_once(dirname(__DIR__, 2) . "/php/php70/classes/autoload.php");

/**
* Full PHPUnit test for the Favorite class
*
* This is a complete PHPUnit test of the Favorite class. It is complete because *ALL* mySQL/PDO enabled methods
* are tested for both invalid and valid inputs.
*
* @see Favorite
* @author Dylan McDonald <dmcdonald21@cnm.edu>
**/
class FavoriteTest extends DataDesignTest {
	/**
	 * timestamp of the Favorite; this starts as null and is assigned later
	 * @var \DateTime $VALID_FAVORITEDATE
	 **/
	protected $VALID_FAVORITEDATE = null;
	/**
	 * Profile that created the favorited the Tweet; this is for foreign key relations
	 * @var \Edu\Cnm\Dmcdonald21\DataDesign\Profile $profile
	 **/
	protected $profile = null;
	/**
	 * Tweet that was favorited; this is for foreign key relations
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
		$this->tweet = new Tweet(null, $this->profile->getProfileId(), "PHPUnit favorite test passing");
		$this->tweet->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_FAVORITEDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Favorite and verify that the actual mySQL data matches
	 **/
	public function testInsertValidFavorite() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");

		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->tweet->getTweetId(), $this->profile->getProfileId(), $this->VALID_FAVORITEDATE);
		$favorite->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoFavorite = Favorite::getFavoriteByTweetIdAndProfileId($this->getPDO(), $this->tweet->getTweetId(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertEquals($pdoFavorite->getTweetId(), $this->tweet->getTweetId());
		$this->assertEquals($pdoFavorite->getProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoFavorite->getFavoriteDate(), $this->VALID_FAVORITEDATE);
	}

	/**
	 * test creating Favorite that makes no sense
	 *
	 * @expectedException TypeError
	 **/
	public function testInsertInvalidFavorite() {
		// create a favorite without foreign keys and watch it fail
		$favorite = new Favorite(null, null, null);
	}

	/**
	 * test creating a Favorite and then deleting it
	 **/
	public function testDeleteValidFavorite() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");

		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->tweet->getTweetId(), $this->profile->getProfileId(), $this->VALID_FAVORITEDATE);
		$favorite->insert($this->getPDO());

		// delete the Favorite from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$favorite->delete($this->getPDO());

		// grab the data from mySQL and enforce the Tweet does not exist
		$pdoFavorite = Favorite::getFavoriteByTweetIdAndProfileId($this->getPDO(), $this->tweet->getTweetId(), $this->profile->getProfileId());
		$this->assertNull($pdoFavorite);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("favorite"));
	}

	/**
	 * test inserting a Favorite and regrabbing it from mySQL
	 **/
	public function testGetValidFavoriteByTweetIdAndProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");

		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->tweet->getTweetId(), $this->profile->getProfileId(), $this->VALID_FAVORITEDATE);
		$favorite->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoFavorite = Favorite::getFavoriteByTweetIdAndProfileId($this->getPDO(), $this->tweet->getTweetId(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertEquals($pdoFavorite->getTweetId(), $this->tweet->getTweetId());
		$this->assertEquals($pdoFavorite->getProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoFavorite->getFavoriteDate(), $this->VALID_FAVORITEDATE);
	}

	/**
	 * test grabbing a Favorite that does not exist
	 **/
	public function testGetInvalidFavoriteByTweetIdAndProfileId() {
		// grab a tweet id and profile id that exceeds the maximum allowable tweet id and profile id
		$favorite = Favorite::getFavoriteByTweetIdAndProfileId($this->getPDO(), DataDesignTest::INVALID_KEY, DataDesignTest::INVALID_KEY);
		$this->assertNull($favorite);
	}

	/**
	 * test grabbing a Favorite by tweet id
	 **/
	public function testGetValidFavoriteByTweetId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");

		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->tweet->getTweetId(), $this->profile->getProfileId(), $this->VALID_FAVORITEDATE);
		$favorite->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Favorite::getFavoriteByTweetId($this->getPDO(), $this->tweet->getTweetId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Dmcdonald21\\DataDesign\\Favorite", $results);

		// grab the result from the array and validate it
		$pdoFavorite = $results[0];
		$this->assertEquals($pdoFavorite->getTweetId(), $this->tweet->getTweetId());
		$this->assertEquals($pdoFavorite->getProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoFavorite->getFavoriteDate(), $this->VALID_FAVORITEDATE);
	}

	/**
	 * test grabbing a Favorite by a tweet id that does not exist
	 **/
	public function testGetInvalidFavoriteByTweetId() {
		// grab a tweet id that exceeds the maximum allowable tweet id
		$favorite = Favorite::getFavoriteByTweetId($this->getPDO(), DataDesignTest::INVALID_KEY);
		$this->assertCount(0, $favorite);
	}

	/**
	 * test grabbing a Favorite by profile id
	 **/
	public function testGetValidFavoriteByProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");

		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->tweet->getTweetId(), $this->profile->getProfileId(), $this->VALID_FAVORITEDATE);
		$favorite->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Favorite::getFavoriteByProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Dmcdonald21\\DataDesign\\Favorite", $results);

		// grab the result from the array and validate it
		$pdoFavorite = $results[0];
		$this->assertEquals($pdoFavorite->getTweetId(), $this->tweet->getTweetId());
		$this->assertEquals($pdoFavorite->getProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoFavorite->getFavoriteDate(), $this->VALID_FAVORITEDATE);
	}

	/**
	 * test grabbing a Favorite by a profile id that does not exist
	 **/
	public function testGetInvalidFavoriteByProfileId() {
		// grab a tweet id that exceeds the maximum allowable profile id
		$favorite = Favorite::getFavoriteByProfileId($this->getPDO(), DataDesignTest::INVALID_KEY);
		$this->assertCount(0, $favorite);
	}
}
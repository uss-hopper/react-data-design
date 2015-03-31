<?php
// grab the project test parameters
require_once("data-design.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/php/classes/profile.php");


/**
 * Full PHPUnit test for the Profile class
 *
 * This is a complete PHPUnit test of the Profile class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Profile
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class ProfileTest extends DataDesignTest {
	/**
	 * valid at handle to use
	 * @var string $VALID_ATHANDLE
	 **/
	protected $VALID_ATHANDLE = "@phpunit";
	/**
	 * second valid at handle to use
	 * @var string $VALID_ATHANDLE2
	 **/
	protected $VALID_ATHANDLE2 = "@passingtests";
	/**
	 * valid email to use
	 * @var string $VALID_EMAIL
	 **/
	protected $VALID_EMAIL = "test@phpunit.de";
	/**
	 * valid phone number to use
	 * @var string $VALID_PHONE
	 **/
	protected $VALID_PHONE = "+12125551212";

	/**
	 * test inserting a valid Profile and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getAtHandle(), $this->VALID_ATHANDLE);
		$this->assertEquals($pdoProfile->getEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getPhone(), $this->VALID_PHONE);
	}

	/**
	 * test inserting a Profile that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidProfile() {
		// create a profile with a non null profileId and watch it fail
		$profile = new Profile(DataDesignTest::INVALID_KEY, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
		$profile->insert($this->getPDO());
	}

	/**
	 * test inserting a Profile, editing it, and then updating it
	 **/
	public function testUpdateValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
		$profile->insert($this->getPDO());

		// edit the Profile and update it in mySQL
		$profile->setAtHandle($this->VALID_ATHANDLE2);
		$profile->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getAtHandle(), $this->VALID_ATHANDLE2);
		$this->assertEquals($pdoProfile->getEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getPhone(), $this->VALID_PHONE);
	}

	/**
	 * test updating a Profile that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidProfile() {
		// create a Profile and try to update it without actually inserting it
		$profile = new Profile(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
		$profile->update($this->getPDO());
	}

	/**
	 * test creating a Profile and then deleting it
	 **/
	public function testDeleteValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
		$profile->insert($this->getPDO());

		// delete the Profile from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$profile->delete($this->getPDO());

		// grab the data from mySQL and enforce the Profile does not exist
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertNull($pdoProfile);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("profile"));
	}

	/**
	 * test deleting a Profile that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidProfile() {
		// create a Profile and try to delete it without actually inserting it
		$profile = new Profile(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
		$profile->update($this->getPDO());
	}

	/**
	 * test inserting a Profile and regrabbing it from mySQL
	 **/
	public function testGetValidProfileByProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getAtHandle(), $this->VALID_ATHANDLE);
		$this->assertEquals($pdoProfile->getEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getPhone(), $this->VALID_PHONE);
	}

	/**
	 * test grabbing a Profile that does not exist
	 **/
	public function testGetInvalidProfileByProfileId() {
		// grab a profile id that exceeds the maximum allowable profile id
		$profile = Profile::getProfileByProfileId($this->getPDO(), DataDesignTest::INVALID_KEY);
		$this->assertNull($profile);
	}

	public function testGetValidProfileByAtHandle() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByAtHandle($this->getPDO(), $this->VALID_ATHANDLE);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getAtHandle(), $this->VALID_ATHANDLE);
		$this->assertEquals($pdoProfile->getEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getPhone(), $this->VALID_PHONE);
	}

	/**
	 * test grabbing a Profile by at handle that does not exist
	 **/
	public function testGetInvalidProfileByAtHandle() {
		// grab an at handle that does not exist
		$profile = Profile::getProfileByAtHandle($this->getPDO(), "@doesnotexist");
		$this->assertNull($profile);
	}

	/**
	 * test grabbing a Profile by email
	 **/
	public function testGetValidProfileByEmail() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ATHANDLE, $this->VALID_EMAIL, $this->VALID_PHONE);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByEmail($this->getPDO(), $profile->getEmail());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getAtHandle(), $this->VALID_ATHANDLE);
		$this->assertEquals($pdoProfile->getEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getPhone(), $this->VALID_PHONE);
	}

	/**
	 * test grabbing a Profile by an email that does not exists
	 **/
	public function testGetInvalidProfileByEmail() {
		// grab an email that does not exist
		$profile = Profile::getProfileByEmail($this->getPDO(), "does@not.exist");
		$this->assertNull($profile);
	}
}
?>
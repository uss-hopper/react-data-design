<?php
require_once("generic-test.php");
require_once("../php/classes/profile.php");

class ProfileTest extends GenericTest {

	public function testInsertValidProfile() {
		$numRows = $this->getConnection()->getRowCount("profile");
		$profile = new Profile(null, "@phpunit", "test@phpunit.de", "+12125551212");
		$profile->insert(self::$pdo);
		$pdoProfile = Profile::getProfileByProfileId(self::$pdo, $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getAtHandle(), "@phpunit");
		$this->assertEquals($pdoProfile->getEmail(), "test@phpunit.de");
		$this->assertEquals($pdoProfile->getPhone(), "+12125551212");
	}
}
?>
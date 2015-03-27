<?php
require_once("generic-test.php");
require_once("../php/classes/profile.php");

class ProfileTest extends GenericTest {

	public function testInsertValidProfile() {
		$numRows = $this->getConnection()->getRowCount("profile");
		$profile = new Profile(null, "@phpunit", "test@phpunit.de", "+12125551212");
		$profile->insert(self::$pdo);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
	}
}
?>
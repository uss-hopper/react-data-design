<?php
// grab the encrypted properties file
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * Abstract class containing universal and project specific mySQL parameters
 *
 * This class is designed to lay the foundation of the unit tests per project. It loads the all the database
 * parameters about the project so that table specific tests can share the parameters in on place. To use it:
 *
 * 1. Rename the class from DataDesignTest to a project specific name (e.g., ProjectNameTest)
 * 2. Modify DataDesignTest::getDataSet() to include all the tables in your project.
 * 3. Modify DataDesignTest::getConnection() to include the correct mySQL properties file.
 * 4. Have all table specific tests include this class.
 *
 * *NOTE*: Tables must be added in the order they were created in step (2).
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
abstract class DataDesignTest extends PHPUnit_Extensions_Database_TestCase {
	/**
	 * invalid id to use for an INT UNSIGNED field (maximum allowed INT UNSIGTNED in mySQL) + 1
	 * @see <https://dev.mysql.com/doc/refman/5.6/en/integer-types.html>
	 * @var int INVALID_KEY
	 **/
	const INVALID_KEY = 4294967296;

	/**
	 * PHPUnit database connection interface
	 * @var PHPUnit_Extensions_Database_DB_IDatabaseConnection $connection
	 **/
	protected $connection = null;

	/**
	 * assembles the table from the schema and provides it to PHPUnit
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_QueryDataSet assembled schema for PHPUnit
	 **/
	public final function getDataSet() {
		$dataset = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());

		// add all the tables for the project here
		// THESE TABLES *MUST* BE LISTED IN THE SAME ORDER THEY WERE CREATED!!!!
		$dataset->addTable("profile");
		$dataset->addTable("tweet");
		$dataset->addTable("favorite");
		return($dataset);
	}

	/**
	 * templates the setUp method that runs before each test; this method expunges the database before each run
	 *
	 * @see <https://phpunit.de/manual/current/en/fixtures.html#fixtures.more-setup-than-teardown>
	 * @see <https://github.com/sebastianbergmann/dbunit/issues/37>
	 * @return PHPUnit_Extensions_Database_Operation_Composite array containing delete and insert commands
	 **/
	public final function getSetUpOperation() {
		return new PHPUnit_Extensions_Database_Operation_Composite(array(
			PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL(),
			PHPUnit_Extensions_Database_Operation_Factory::INSERT()
		));
	}

	/**
	 * templates the tearDown method that runs after each test; this method expunges the database after each run
	 *
	 * @return PHPUnit_Extensions_Database_Operation_IDatabaseOperation delete command for the database
	 **/
	public final function getTearDownOperation() {
		return(PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL());
	}

	/**
	 * sets up the database connection and provides it to PHPUnit
	 *
	 * @see <https://phpunit.de/manual/current/en/database.html#database.configuration-of-a-phpunit-database-testcase>
	 * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection PHPUnit database connection interface
	 **/
	public final function getConnection() {
		// if the connection hasn't been established, create it
		if($this->connection === null) {
			// grab the encrypted mySQL properties file and create the DSN
			$config = readConfig("/etc/apache2/capstone-mysql/dmcdonald21.ini");
			$dsn = "mysql:host=" . $config["hostname"] . ";dbname=" . $config["database"];
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

			// provide the interface to PHPUnit
			$pdo = new PDO($dsn, $config["username"], $config["password"], $options);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection = $this->createDefaultDBConnection($pdo, $config["database"]);
		}
		return($this->connection);
	}

	/**
	 * returns the actual PDO object; this is a convenience method
	 *
	 * @return PDO active PDO object
	 **/
	public final function getPDO() {
		return($this->getConnection()->getConnection());
	}
}
?>
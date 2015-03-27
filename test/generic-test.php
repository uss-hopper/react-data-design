<?php
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

abstract class GenericTest extends PHPUnit_Extensions_Database_TestCase {
	static protected $pdo = null;

	protected $connection = null;

	public final function getDataSet() {
		$dataset = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
		$dataset->addTable("favorite");
		$dataset->addTable("tweet");
		$dataset->addTable("profile");
		return($dataset);
	}

	public function setUp() {
		$connection = $this->getConnection();
		$connection->getConnection()->query("SET FOREIGN_KEY_CHECKS = 0");
		parent::setUp();
		$connection->getConnection()->query("SET FOREIGN_KEY_CHECKS = 1");
	}

	public function getTearDownOperation() {
		return(PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL());
	}

	public final function getConnection() {
		if($this->connection === null) {
			$config = readConfig("/etc/apache2/capstone-mysql/dmcdonald21.ini");
			$dsn = "mysql:host=" . $config["hostname"] . ";dbname=" . $config["database"];
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
			if(self::$pdo === null) {
				self::$pdo = new PDO($dsn, $config["username"], $config["password"], $options);
				self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			$this->connection = $this->createDefaultDBConnection(self::$pdo, $config["database"]);
		}
		return($this->connection);
	}
}
?>
<?php
namespace Edu\Cnm\DataDesign\Test;

// grab the project test parameters
require_once("DataDesignTest.php");

/**
 * PHPUnit Test for the Haversine stored procedure
 *
 * This unit test demonstrates and validates the execution of a stored procedure to calculate the distance
 * between two points on earth given their GPS coordinates
 *
 * @see http://en.wikipedia.org/wiki/Haversine_formula Haversine Formula
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class HaversineTest extends DataDesignTest {
	/**
	 * origin of where distance is measured from
	 * @var array $VALID_ORIGIN
	 **/
	protected $VALID_ORIGIN = [36.12, -86.67];

	/**
	 * destination of where distance is measured to
	 * @var array $VALID_DESTINATION
	 **/
	protected $VALID_DESTINATION = [33.94, -118.4];

	/**
	 * distance from the origin to the destination
	 * @var float $VALID_DISTANCE
	 **/
	protected $VALID_DISTANCE = 1793.55595844;

	/**
	 * test the haversine algorithm against known inputs
	 **/
	public function testHaversine() {
		// create a query template to CALL the stored procedure
		$pdo = $this->getPDO();
		$query = "SELECT haversine(:originLong, :originLat, :destinationLong, :destinationLat) AS distance";
		$statement = $pdo->prepare($query);

		// bind the parameters to the stored procedure
		$parameters = array("originLat" => $this->VALID_ORIGIN[0], "originLong" => $this->VALID_ORIGIN[1],
							"destinationLat" => $this->VALID_DESTINATION[0], "destinationLong" => $this->VALID_DESTINATION[1]);
		$statement->execute($parameters);
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$result = $statement->fetch();
		$distance = $result["distance"];

		// assert the answer is the expected answer within a margin of error (needed for doubles)
		$this->assertEquals($distance, $this->VALID_DISTANCE, "", 0.0001);
	}
}
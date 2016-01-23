<?php
namespace Edu\Cnm\Dmcdonald21\DataDesign;

/**
 * Trait to Validate a mySQL Date
 *
 * This trait will inject a private method to validate a mySQL style date (e.g., 2016-01-15 15:32:48). It will
 * convert a string representation to a DateTime object or throw an exception.
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
trait ValidateDate {
	/**
	 * custom filter for mySQL style dates
	 *
	 * Converts a string to a DateTime object or false if invalid. This is designed to be used within a mutator method.
	 *
	 * @param mixed $newDate date to validate
	 * @return \DateTime DateTime object containing the validated date
	 * @see http://php.net/manual/en/class.datetime.php PHP's DateTime class
	 * @throws \InvalidArgumentException if the date is in an invalid format
	 * @throws \RangeException if the date is not a Gregorian date
	 **/
	private static function validateDate($newDate) {
		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newDate) === true && get_class($newDate) === "DateTime") {
			return($newDate);
		}

		// treat the date as a mySQL date string: Y-m-d H:i:s
		$newDate = trim($newDate);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newDate, $matches)) !== 1) {
			throw(new \InvalidArgumentException("date is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year = intval($matches[1]);
		$month = intval($matches[2]);
		$day = intval($matches[3]);
		$hour = intval($matches[4]);
		$minute = intval($matches[5]);
		$second = intval($matches[6]);
		if(checkdate($month, $day, $year) === false) {
			throw(new \RangeException("date is not a Gregorian date"));
		}

		// verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0  || $second >= 60) {
			throw(new \RangeException("date is not a valid wall clock time"));
		}

		// if we got here, the date is clean
		$newDate = \DateTime::createFromFormat("Y-m-d H:i:s", $newDate);
		return($newDate);
	}
}
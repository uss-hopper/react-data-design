<?php
/**
 * custom filter for mySQL style dates
 *
 * Converts a string to a DateTime object or false if invalid. This is designed to be used with filter_var():
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * filter_var($string, FILTER_CALLBACK, array("options" => "validateDate"));
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *
 * @param mixed $newDate date to validate
 * @return mixed DateTime object containing the validated date or false if invalid
 * @see <http://php.net/manual/en/class.datetime.php>
 * @see <http://php.net/manual/en/filter.filters.misc.php>
 **/
function validateDate($newDate) {
	// base case: if the date is a DateTime object, there's no work to be done
	if(is_object($newDate) === true && get_class($newDate) === "DateTime") {
		return($newDate);
	}

	// treat the date as a mySQL date string: Y-m-d H:i:s
	$newDate = trim($newDate);
	if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newDate, $matches)) !== 1) {
		return(false);
	}

	// verify the date is really a valid calendar date
	$year   = intval($matches[1]);
	$month  = intval($matches[2]);
	$day    = intval($matches[3]);
	$hour   = intval($matches[4]);
	$minute = intval($matches[5]);
	$second = intval($matches[6]);
	if(checkdate($month, $day, $year) === false) {
		return(false);
	}

	// verify the time is really a valid wall clock time
	if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0  || $second >= 60) {
		return(false);
	}

	// if we got here, the date is clean
	$newDate = DateTime::createFromFormat("Y-m-d H:i:s", $newDate);
	return($newDate);
}
?>
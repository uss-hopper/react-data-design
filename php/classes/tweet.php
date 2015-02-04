<?php
/**
 * Small Cross Section of a Twitter like Message
 *
 * This Tweet can be considered a small example of what services like Twitter store when messages are sent and
 * received using Twitter. This can easily be extended to emulate more features of Twitter.
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class Tweet {
	/**
	 * id for this Tweet; this is the primary key
	 **/
	private $tweetId;
	/**
	 * id of the Profile that sent this Tweet; this is a foreign key
	 **/
	private $profileId;
	/**
	 * actual textual content of this Tweet
	 **/
	private $tweetContent;
	/**
	 * date and time this Tweet was sent, in a PHP DateTime object
	 **/
	private $tweetDate;

	/**
	 * constructor for this Tweet
	 *
	 * @param mixed $newTweetId id of this Tweet or null if a new Tweet
	 * @param int $newProfileId id of the Profile that sent this Tweet
	 * @param string $newTweetContent string containing actual tweet data
	 * @param mixed $newTweetDate date and time Tweet was sent or null if set to current date and time
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 **/
	public function __construct($newTweetId, $newProfileId, $newTweetContent, $newTweetDate = null) {
		try {
			$this->setTweetId($newTweetId);
			$this->setProfileId($newProfileId);
			$this->setTweetContent($newTweetContent);
			$this->setTweetDate($newTweetDate);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for tweet id
	 *
	 * @return mixed value of tweet id
	 **/
	public function getTweetId() {
		return($this->tweetId);
	}

	/**
	 * mutator method for tweet id
	 *
	 * @param mixed $newTweetId new value of tweet id
	 * @throws InvalidArgumentException if $newTweetId is not an integer
	 * @throws RangeException if $newTweetId is not positive
	 **/
	public function setTweetId($newTweetId) {
		// base case: if the tweet id is null, this a new tweet without a mySQL assigned id (yet)
		if($newTweetId === null) {
			$this->tweetId = null;
			return;
		}

		// verify the profile id is valid
		$newTweetId = filter_var($newTweetId, FILTER_VALIDATE_INT);
		if($newTweetId === false) {
			throw(new InvalidArgumentException("tweet id is not a valid integer"));
		}

		// verify the profile id is positive
		if($newTweetId <= 0) {
			throw(new RangeException("tweet id is not positive"));
		}

		// convert and store the profile id
		$this->tweetId = intval($newTweetId);
	}

	/**
	 * accessor method for profile id
	 *
	 * @return int value of profile id
	 **/
	public function getProfileId() {
		return($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param int $newProfileId new value of profile id
	 * @throws InvalidArgumentException if $newProfileId is not an integer or not positive
	 * @throws RangeException if $newProfileId is not positive
	 **/
	public function setProfileId($newProfileId) {
		// verify the profile id is valid
		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new InvalidArgumentException("profile id is not a valid integer"));
		}

		// verify the profile id is positive
		if($newProfileId <= 0) {
			throw(new RangeException("profile id is not positive"));
		}

		// convert and store the profile id
		$this->profileId = intval($newProfileId);
	}

	/**
	 * accessor method for tweet content
	 *
	 * @return string value of tweet content
	 **/
	public function getTweetContent() {
		return($this->tweetContent);
	}

	/**
	 * mutator method for tweet content
	 *
	 * @param string $newTweetContent new value of tweet content
	 * @throws InvalidArgumentException if $newTweetContent is not a string or insecure
	 * @throws RangeException if $newTweetContent is > 140 characters
	 **/
	public function setTweetContent($newTweetContent) {
		// verify the tweet content is secure
		$newTweetContent = trim($newTweetContent);
		$newTweetContent = filter_var($newTweetContent, FILTER_SANITIZE_STRING);
		if(empty($newTweetContent) === true) {
			throw(new InvalidArgumentException("tweet content is empty or insecure"));
		}

		// verify the tweet content will fit in the database
		if(strlen($newTweetContent) > 140) {
			throw(new RangeException("tweet content too large"));
		}

		// store the tweet content
		$this->tweetContent = $newTweetContent;
	}

	/**
	 * accessor method for tweet date
	 *
	 * @return DateTime value of tweet date
	 **/
	public function getTweetDate() {
		return($this->tweetDate);
	}

	/**
	 * mutator method for tweet date
	 *
	 * @param mixed $newTweetDate tweet date as a DateTime object or string (or null to load the current time)
	 * @throws InvalidArgumentException if $newTweetDate is not a valid object or string
	 * @throws RangeException if $newTweetDate is a date that does not exist
	 **/
	public function setTweetDate($newTweetDate) {
		// base case: if the date is null, use the current date and time
		if($newTweetDate === null) {
			$this->tweetDate = new DateTime();
			return;
		}

		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newTweetDate) === true && get_class($newTweetDate) === "DateTime") {
			$this->tweetDate = $newTweetDate;
			return;
		}

		// treat the date as a mySQL date string: Y-m-d H:i:s
		$newTweetDate = trim($newTweetDate);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newTweetDate, $matches)) !== 1) {
			throw(new InvalidArgumentException("tweet date is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year   = intval($matches[1]);
		$month  = intval($matches[2]);
		$day    = intval($matches[3]);
		$hour   = intval($matches[4]);
		$minute = intval($matches[5]);
		$second = intval($matches[6]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("tweet date $newTweetDate is not a Gregorian date"));
		}

		// verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0  || $second >= 60) {
			throw(new RangeException("tweet date $newTweetDate is not a valid time"));
		}

		// store the tweet date
		$newTweetDate = DateTime::createFromFormat("Y-m-d H:i:s", $newTweetDate);
		$this->tweetDate = $newTweetDate;
	}

	/**
	 * inserts this Tweet into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the tweetId is null (i.e., don't insert a tweet that already exists)
		if($this->tweetId !== null) {
			throw(new mysqli_sql_exception("not a new tweet"));
		}

		// create query template
		$query     = "INSERT INTO tweet(profileId, tweetContent, tweetDate) VALUES(?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$formattedDate = $this->tweetDate->format("Y-m-d H:i:s");
		$wasClean      = $statement->bind_param("iss", $this->profileId, $this->tweetContent, $formattedDate);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null tweetId with what mySQL just gave us
		$this->tweetId = $mysqli->insert_id;

		// clean up the statement
		$statement->close();
	}


	/**
	 * deletes this Tweet from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the tweetId is not null (i.e., don't delete a tweet that hasn't been inserted)
		if($this->tweetId === null) {
			throw(new mysqli_sql_exception("unable to delete a tweet that does not exist"));
		}

		// create query template
		$query     = "DELETE FROM tweet WHERE tweetId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->tweetId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// clean up the statement
		$statement->close();
	}

	/**
	 * updates this Tweet in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the tweetId is not null (i.e., don't update a tweet that hasn't been inserted)
		if($this->tweetId === null) {
			throw(new mysqli_sql_exception("unable to update a tweet that does not exist"));
		}

		// create query template
		$query     = "UPDATE tweet SET profileId = ?, tweetContent = ?, tweetDate = ? WHERE tweetId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$formattedDate = $this->tweetDate->format("Y-m-d H:i:s");
		$wasClean = $statement->bind_param("issi",  $this->profileId, $this->tweetContent, $formattedDate, $this->tweetId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// clean up the statement
		$statement->close();
	}

	/**
	 * gets the Tweet by content
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $tweetContent tweet content to search for
	 * @return mixed array of Tweets found, Tweets found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getTweetByTweetContent(&$mysqli, $tweetContent) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$tweetContent = trim($tweetContent);
		$tweetContent = filter_var($tweetContent, FILTER_SANITIZE_STRING);

		// create query template
		$query     = "SELECT tweetId, profileId, tweetContent, tweetDate FROM tweet WHERE tweetContent LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the tweet content to the place holder in the template
		$tweetContent = "%$tweetContent%";
		$wasClean = $statement->bind_param("s", $tweetContent);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build an array of tweet
		$tweets = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$tweet    = new Tweet($row["tweetId"], $row["profileId"], $row["tweetContent"], $row["tweetDate"]);
				$tweets[] = $tweet;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfTweets = count($tweets);
		if($numberOfTweets === 0) {
			return(null);
		} else if($numberOfTweets === 1) {
			return($tweets[0]);
		} else {
			return($tweets);
		}
	}
}
?>
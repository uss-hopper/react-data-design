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
	 * @param resource $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(&$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the tweetId is null (i.e., don't insert a tweet that already exists)
		if($this->tweetId !== null) {
			throw(new PDOException("not a new tweet"));
		}

		// create query template
		$query     = "INSERT INTO tweet(profileId, tweetContent, tweetDate) VALUES(:profileId, :tweetContent, :tweetDate)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->tweetDate->format("Y-m-d H:i:s");
		$parameters = array("profileId" => $this->profileId, "tweetContent" => $this->tweetContent, "tweetDate" => $formattedDate);
		$statement->execute($parameters);

		// update the null tweetId with what mySQL just gave us
		$this->tweetId = intval($pdo->lastInsertId());
	}


	/**
	 * deletes this Tweet from mySQL
	 *
	 * @param resource $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function delete(&$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the tweetId is not null (i.e., don't delete a tweet that hasn't been inserted)
		if($this->tweetId === null) {
			throw(new PDOException("unable to delete a tweet that does not exist"));
		}

		// create query template
		$query     = "DELETE FROM tweet WHERE tweetId = :tweetId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = array("tweetId" => $this->tweetId);
		$statement->execute($parameters);
	}

	/**
	 * updates this Tweet in mySQL
	 *
	 * @param resource $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function update(&$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the tweetId is not null (i.e., don't update a tweet that hasn't been inserted)
		if($this->tweetId === null) {
			throw(new PDOException("unable to update a tweet that does not exist"));
		}

		// create query template
		$query     = "UPDATE tweet SET profileId = :profileId, tweetContent = :tweetContent, tweetDate = :tweetDate WHERE tweetId = :tweetId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->tweetDate->format("Y-m-d H:i:s");
		$parameters = array("profileId" => $this->profileId, "tweetContent" => $this->tweetContent, "tweetDate" => $formattedDate, "tweetId" => $this->tweetId);
		$statement->execute($parameters);
	}

	/**
	 * gets the Tweet by content
	 *
	 * @param resource $pdo pointer to PDO connection, by reference
	 * @param string $tweetContent tweet content to search for
	 * @return mixed array of Tweets found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getTweetByTweetContent(&$pdo, $tweetContent) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize the description before searching
		$tweetContent = trim($tweetContent);
		$tweetContent = filter_var($tweetContent, FILTER_SANITIZE_STRING);
		if(empty($tweetContent) === true) {
			throw(new PDOException("tweet content is invalid"));
		}

		// create query template
		$query     = "SELECT tweetId, profileId, tweetContent, tweetDate FROM tweet WHERE tweetContent LIKE :tweetContent";
		$statement = $pdo->prepare($query);

		// bind the tweet content to the place holder in the template
		$tweetContent = "%$tweetContent%";
		$parameters = array("tweetContent" => $tweetContent);
		$statement->execute($parameters);

		// build an array of tweets
		$tweets = array();
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$tweet    = new Tweet($row["tweetId"], $row["profileId"], $row["tweetContent"], $row["tweetDate"]);
				$tweets[] = $tweet;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfTweets = count($tweets);
		if($numberOfTweets === 0) {
			return(null);
		} else {
			return($tweets);
		}
	}

	/**
	 * gets the Tweet by tweetId
	 *
	 * @param resource $pdo pointer to PDO connection, by reference
	 * @param int $tweetId tweet content to search for
	 * @return mixed Tweet found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getTweetByTweetId(&$pdo, $tweetId) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize the tweetId before searching
		$tweetId = filter_var($tweetId, FILTER_VALIDATE_INT);
		if($tweetId === false) {
			throw(new PDOException("tweet id is not an integer"));
		}
		if($tweetId <= 0) {
			throw(new PDOException("tweet id is not positive"));
		}

		// create query template
		$query     = "SELECT tweetId, profileId, tweetContent, tweetDate FROM tweet WHERE tweetId = :tweetId";
		$statement = $pdo->prepare($query);

		// bind the tweet content to the place holder in the template
		$parameters = array("tweetId" => $tweetId);
		$statement->execute($parameters);

		// grab the tweet from mySQL
		try {
			$tweet = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row   = $statement->fetch();
			if($row !== false) {
				$tweet = new Tweet($row["tweetId"], $row["profileId"], $row["tweetContent"], $row["tweetDate"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($tweet);
	}

	/**
	 * gets all Tweets
	 *
	 * @param resource $pdo pointer to PDO connection, by reference
	 * @return mixed array of Tweets found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAllTweets(&$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// create query template
		$query = "SELECT tweetId, profileId, tweetContent, tweetDate FROM tweet";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of tweets
		$tweets = array();
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$tweet = new Tweet($row["tweetId"], $row["profileId"], $row["tweetContent"], $row["tweetDate"]);
				$tweets[] = $tweet;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfTweets = count($tweets);
		if($numberOfTweets === 0) {
			return (null);
		} else {
			return ($tweets);
		}
	}
}

/**
 * full shakedown code
try {
	$dsn = "mysql:host=--HOSTNAME--;dbname=--DATABASE--";
	$username = "--USERNAME--";
	$password = "--PASSWORD--";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $username, $password, $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$tweet = new Tweet(null, 1, "Tweets are DBO enabled");
	$tweet->insert($pdo);
	var_dump($tweet);
	$tweet->setTweetContent("Tweets are fully DBO enabled");
	$tweet->update($pdo);
	$pdoTweet = Tweet::getTweetByTweetId($pdo, $tweet->getTweetId());
	var_dump($pdoTweet);
	$tweet->delete($pdo);
	$pdoTweet = Tweet::getTweetByTweetId($pdo, $tweet->getTweetId());
	var_dump($pdoTweet);
} catch(PDOException $pdoException) {
	echo "Exception: " . $pdoException->getMessage();
}
 **/
?>
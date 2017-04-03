<?php
namespace Edu\Cnm\DataDesign;

require_once("autoload.php");

/**
 * Cross Section of a Twitter Like
 *
 * This is a cross section of what probably occurs when a user likes a Tweet. It is an intersection table (weak
 * entity) from an m-to-n relationship between Profile and Tweet.
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 3.0.0
 **/
class Like implements \JsonSerializable {
	use ValidateDate;

	/**
	 * id of the Tweet being liked; this is a component of a composite primary key (and a foreign key)
	 * @var int $likeTweetId
	 **/
	private $likeTweetId;
	/**
	 * id of the Profile who liked; this is a component of a composite primary key (and a foreign key)
	 * @var int $likeProfileId
	 **/
	private $likeProfileId;
	/**
	 * date and time the tweet was liked
	 * @var \DateTime $likeDate
	 **/
	private $likeDate;

	/**
	 * constructor for this Like
	 *
	 * @param int $newLikeProfileId id of the parent Profile
	 * @param int $newLikeTweetId id of the parent Tweet
	 * @param \DateTime|null $newLikeDate date the tweet was liked (or null for current time)
	 * @throws \Exception if some other exception occurs
	 * @throws \TypeError if data types violate type hints
	 */
	public function __construct(int $newLikeProfileId, int $newLikeTweetId, $newLikeDate = null) {
		// use the mutators to do the work for us!
		try {
			$this->setLikeProfileId($newLikeProfileId);
			$this->setLikeTweetId($newLikeTweetId);
			$this->setLikeDate($newLikeDate);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profile id
	 *
	 * @return int value of profile id
	 **/
	public function getLikeProfileId() {
		return ($this->likeProfileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param int $newProfileId new value of profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not an integer
	 **/
	public function setLikeProfileId(int $newProfileId) {
		// verify the profile id is positive
		if($newProfileId <= 0) {
			throw(new \RangeException("profile id is not positive"));
		}

		// convert and store the profile id
		$this->likeProfileId = $newProfileId;
	}

	/**
	 * accessor method for tweet id
	 *
	 * @return int value of tweet id
	 **/
	public function getLikeTweetId() {
		return ($this->likeTweetId);
	}

	/**
	 * mutator method for tweet id
	 *
	 * @param int $newLikeTweetId new value of tweet id
	 * @throws \RangeException if $newTweetId is not positive
	 * @throws \TypeError if $newTweetId is not an integer
	 **/
	public function setLikeTweetId(int $newLikeTweetId) {
		// verify the tweet id is positive
		if($newLikeTweetId <= 0) {
			throw(new \RangeException("tweet id is not positive"));
		}

		// convert and store the profile id
		$this->likeTweetId = $newLikeTweetId;
	}

	/**
	 * accessor method for like date
	 *
	 * @return \DateTime value of like date
	 **/
	public function getLikeDate() {
		return ($this->likeDate);
	}

	/**
	 * mutator method for like date
	 *
	 * @param \DateTime|string|null $newLikeDate like date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newLikeDate is not a valid object or string
	 * @throws \RangeException if $newLikeDate is a date that does not exist
	 **/
	public function setLikeDate($newLikeDate) {
		// base case: if the date is null, use the current date and time
		if($newLikeDate === null) {
			$this->likeDate = new \DateTime();
			return;
		}

		// store the like date
		try {
			$newLikeDate = self::validateDateTime($newLikeDate);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		}
		$this->likeDate = $newLikeDate;
	}

	/**
	 * inserts this Like into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// ensure the object exists before inserting
		if($this->likeProfileId === null || $this->likeTweetId === null) {
			throw(new \PDOException("not a valid like"));
		}

		// create query template
		$query = "INSERT INTO `like`(likeProfileId, likeTweetId, likeDate) VALUES(:likeProfileId, :likeTweetId, :likeDate)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->likeDate->format("Y-m-d H:i:s");
		$parameters = ["likeProfileId" => $this->likeProfileId, "likeTweetId" => $this->likeTweetId, "likeDate" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Like from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// ensure the object exists before deleting
		if($this->likeProfileId === null || $this->likeTweetId === null) {
			throw(new \PDOException("not a valid like"));
		}

		// create query template
		$query = "DELETE FROM `like` WHERE likeProfileId = :likeProfileId AND likeTweetId = :likeTweetId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["likeProfileId" => $this->likeProfileId, "likeTweetId" => $this->likeTweetId];
		$statement->execute($parameters);
	}

	/**
	 * gets the Like by tweet id and profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $likeProfileId profile id to search for
	 * @param int $likeTweetId tweet id to search for
	 * @return Like|null Like found or null if not found
	 */
	public static function getLikeByLikeTweetIdAndLikeProfileId(\PDO $pdo, int $likeProfileId, int $likeTweetId) {
		// sanitize the tweet id and profile id before searching
		if($likeProfileId <= 0) {
			throw(new \PDOException("profile id is not positive"));
		}
		if($likeTweetId <= 0) {
			throw(new \PDOException("tweet id is not positive"));
		}

		// create query template
		$query = "SELECT likeProfileId, likeTweetId, likeDate FROM `like` WHERE likeProfileId = :likeProfileId AND likeTweetId = :likeTweetId";
		$statement = $pdo->prepare($query);

		// bind the tweet id and profile id to the place holder in the template
		$parameters = ["likeProfileId" => $likeProfileId, "likeTweetId" => $likeTweetId];
		$statement->execute($parameters);

		// grab the like from mySQL
		try {
			$like = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$like = new Like($row["likeProfileId"], $row["likeTweetId"], $row["likeDate"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			var_dump($exception->getTrace());
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($like);
	}

	/**
	 * gets the Like by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $likeProfileId profile id to search for
	 * @return \SplFixedArray SplFixedArray of Likes found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getLikeByLikeProfileId(\PDO $pdo, int $likeProfileId) {
		// sanitize the profile id
		if($likeProfileId <= 0) {
			throw(new \PDOException("profile id is not positive"));
		}

		// create query template
		$query = "SELECT likeProfileId, likeTweetId, likeDate FROM `like` WHERE likeProfileId = :likeProfileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["likeProfileId" => $likeProfileId];
		$statement->execute($parameters);

		// build an array of likes
		$likes = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$like = new Like($row["likeProfileId"], $row["likeTweetId"], $row["likeDate"]);
				$likes[$likes->key()] = $like;
				$likes->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($likes);
	}

	/**
	 * gets the Like by tweet it id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $likeTweetId tweet id to search for
	 * @return \SplFixedArray array of Likes found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getLikeByLikeTweetId(\PDO $pdo, int $likeTweetId) {
		// sanitize the tweet id
		$likeTweetId = filter_var($likeTweetId, FILTER_VALIDATE_INT);
		if($likeTweetId <= 0) {
			throw(new \PDOException("tweet id is not positive"));
		}

		// create query template
		$query = "SELECT likeProfileId, likeTweetId, likeDate FROM `like` WHERE likeTweetId = :likeTweetId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["likeTweetId" => $likeTweetId];
		$statement->execute($parameters);

		// build an array of likes
		$likes = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$like = new Like($row["likeProfileId"], $row["likeTweetId"], $row["likeDate"]);
				$likes[$likes->key()] = $like;
				$likes->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($likes);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["likeDate"] = $this->likeDate->getTimestamp() * 1000;
		return($fields);
	}
}
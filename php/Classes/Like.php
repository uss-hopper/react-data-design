<?php

namespace UssHopper\DataDesign;
require_once("autoload.php");

require_once(dirname(__DIR__) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;

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
	use ValidateUuid;

	/**
	 * id of the Tweet being liked; this is a component of a composite primary key (and a foreign key)
	 * @var Uuid $likeTweetId
	 **/
	private $likeTweetId;
	/**
	 * id of the Profile who liked; this is a component of a composite primary key (and a foreign key)
	 * @var Uuid $likeProfileId
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
	 * @param string|Uuid $newLikeProfileId id of the parent Profile
	 * @param string|Uuid $newLikeTweetId id of the parent Tweet
	 * @param \DateTime|null $newLikeDate date the tweet was liked (or null for current time)
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception is thrown
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 */
	public function __construct( $newLikeProfileId,  $newLikeTweetId, $newLikeDate = null) {
		// use the mutator methods to do the work for us!
		try {
			$this->setLikeProfileId($newLikeProfileId);
			$this->setLikeTweetId($newLikeTweetId);
			$this->setLikeDate($newLikeDate);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {

			// determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profile id
	 *
	 * @return Uuid value of profile id
	 **/
	public function getLikeProfileId() : Uuid {
		return ($this->likeProfileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param string  $newLikeProfileId new value of profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not an integer
	 **/
	public function setLikeProfileId($newLikeProfileId) : void {
		try {
			$uuid = self::validateUuid($newLikeProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile id
		$this->likeProfileId = $uuid;
	}

	/**
	 * accessor method for tweet id
	 *
	 * @return uuid value of tweet id
	 **/
	public function getLikeTweetId() : Uuid{
		return ($this->likeTweetId);
	}

	/**
	 * mutator method for tweet id
	 *
	 * @param string  $newLikeTweetId new value of tweet id
	 * @throws \RangeException if $newTweetId is not positive
	 * @throws \TypeError if $newLikeTweetId is not an integer
	 **/
	public function setLikeTweetId( $newLikeTweetId) : void {
		try {
			$uuid = self::validateUuid($newLikeTweetId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the profile id
		$this->likeTweetId = $uuid;
	}

	/**
	 * accessor method for like date
	 *
	 * @return \DateTime value of like date
	 **/
	public function getLikeDate() : \DateTime {
		return ($this->likeDate);
	}

	/**
	 * mutator method for like date
	 *
	 * @param \DateTime|string|null $newLikeDate like date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newLikeDate is not a valid object or string
	 * @throws \RangeException if $newLikeDate is a date that does not exist
	 **/
	public function setLikeDate($newLikeDate): void {
		// base case: if the date is null, use the current date and time
		if($newLikeDate === null) {
			$this->likeDate = new \DateTime();
			return;
		}

		// store the like date using the ValidateDate trait
		try {
			$newLikeDate = self::validateDateTime($newLikeDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->likeDate = $newLikeDate;
	}

	/**
	 * inserts this Like into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public function insert(\PDO $pdo) : void {
		// create query template
		$query = "INSERT INTO `like`(likeProfileId, likeTweetId, likeDate) VALUES(:likeProfileId, :likeTweetId, :likeDate)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->likeDate->format("Y-m-d H:i:s.u");
		$parameters = ["likeProfileId" => $this->likeProfileId->getBytes(), "likeTweetId" => $this->likeTweetId->getBytes(), "likeDate" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Like from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM `like` WHERE likeProfileId = :likeProfileId AND likeTweetId = :likeTweetId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholders in the template
		$parameters = ["likeProfileId" => $this->likeProfileId->getBytes(), "likeTweetId" => $this->likeTweetId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * gets the Like by tweet id and profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $likeProfileId profile id to search for
	 * @param string $likeTweetId tweet id to search for
	 * @return Like|null Like found or null if not found
	 */
	public static function getLikeByLikeTweetIdAndLikeProfileId(\PDO $pdo, string $likeProfileId, string $likeTweetId) : ?Like {

		//
		try {
			$likeProfileId = self::validateUuid($likeProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		try {
			$likeTweetId = self::validateUuid($likeTweetId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT likeProfileId, likeTweetId, likeDate FROM `like` WHERE likeProfileId = :likeProfileId AND likeTweetId = :likeTweetId";
		$statement = $pdo->prepare($query);

		// bind the tweet id and profile id to the place holder in the template
		$parameters = ["likeProfileId" => $likeProfileId->getBytes(), "likeTweetId" => $likeTweetId->getBytes()];
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
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($like);
	}

	/**
	 * gets the Like by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $likeProfileId profile id to search for
	 * @return \SplFixedArray SplFixedArray of Likes found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public static function getLikeByLikeProfileId(\PDO $pdo, string $likeProfileId) : \SPLFixedArray {
		try {
			$likeProfileId = self::validateUuid($likeProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT likeProfileId, likeTweetId, likeDate FROM `like` WHERE likeProfileId = :likeProfileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["likeProfileId" => $likeProfileId->getBytes()];
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
		return ($likes);
	}

	/**
	 * gets the Like by tweet it id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $likeTweetId tweet id to search for
	 * @return \SplFixedArray array of Likes found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public static function getLikeByLikeTweetId(\PDO $pdo, string $likeTweetId) : \SplFixedArray {
		try {
			$likeTweetId = self::validateUuid($likeTweetId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT likeProfileId, likeTweetId, likeDate FROM `like` WHERE likeTweetId = :likeTweetId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["likeTweetId" => $likeTweetId->getBytes()];
		$statement->execute($parameters);

		// build the array of likes
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
		return ($likes);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);


		//format the date so that the front end can consume it
		$fields["likeProfileId"] = $this->likeProfileId;
		$fields["likeTweetId"] = $this->likeTweetId;
		$fields["likeDate"] = round(floatval($this->likeDate->format("U.u")) * 1000);

		return ($fields);
	}
}
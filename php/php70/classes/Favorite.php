<?php
namespace Edu\Cnm\Dmcdonald21\DataDesign;

require_once("autoload.php");

/**
 * Cross Section of a Twitter Favorite
 *
 * This is a cross section of what probably occurs when a user favorites a Tweet. It is an intersection table (weak
 * entity) from an m-to-n relationship between Profile and Tweet.
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 2.0.0
 **/
class Favorite implements \JsonSerializable {
	use ValidateDate;

	/**
	 * id of the Tweet being favorited; this is a component of a composite primary key (and a foreign key)
	 * @var int $tweetId
	 **/
	private $tweetId;
	/**
	 * id of the Profile who favorited; this is a component of a composite primary key (and a foreign key)
	 * @var int $profileId
	 **/
	private $profileId;
	/**
	 * date and time the tweet was favorited
	 * @var \DateTime $favoriteDate
	 **/
	private $favoriteDate;

	/**
	 * constructor for this Favorite
	 *
	 * @param int $newTweetId id of the parent Tweet
	 * @param int $newProfileId id of the parent Profile
	 * @param \DateTime|null $newFavoriteDate date the tweet was favorited (or null for current time)
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newTweetId = null, int $newProfileId, $newFavoriteDate = null) {
		// use the mutators to do the work for us!
		try {
			$this->setTweetId($newTweetId);
			$this->setProfileId($newProfileId);
			$this->setFavoriteDate($newFavoriteDate);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			// rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			// rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			// rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for tweet id
	 *
	 * @return int value of tweet id
	 **/
	public function getTweetId() {
		return ($this->tweetId);
	}

	/**
	 * mutator method for tweet id
	 *
	 * @param int $newTweetId new value of tweet id
	 * @throws \RangeException if $newTweetId is not positive
	 * @throws \TypeError if $newTweetId is not an integer
	 **/
	public function setTweetId(int $newTweetId) {
		// verify the tweet id is positive
		if($newTweetId <= 0) {
			throw(new \RangeException("tweet id is not positive"));
		}

		// convert and store the profile id
		$this->tweetId = $newTweetId;
	}

	/**
	 * accessor method for profile id
	 *
	 * @return int value of profile id
	 **/
	public function getProfileId() {
		return ($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param int $newProfileId new value of profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not an integer
	 **/
	public function setProfileId(int $newProfileId) {
		// verify the profile id is positive
		if($newProfileId <= 0) {
			throw(new \RangeException("profile id is not positive"));
		}

		// convert and store the profile id
		$this->profileId = $newProfileId;
	}

	/**
	 * accessor method for favorite date
	 *
	 * @return \DateTime value of favorite date
	 **/
	public function getFavoriteDate() {
		return ($this->favoriteDate);
	}

	/**
	 * mutator method for favorite date
	 *
	 * @param \DateTime|string|null $newFavoriteDate favorite date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newFavoriteDate is not a valid object or string
	 * @throws \RangeException if $newFavoriteDate is a date that does not exist
	 **/
	public function setFavoriteDate($newFavoriteDate) {
		// base case: if the date is null, use the current date and time
		if($newFavoriteDate === null) {
			$this->favoriteDate = new \DateTime();
			return;
		}

		// store the favorite date
		try {
			$newFavoriteDate = $this->validateDate($newFavoriteDate);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		}
		$this->favoriteDate = $newFavoriteDate;
	}

	/**
	 * inserts this Favorite into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// ensure the object exists before inserting
		if($this->profileId === null || $this->tweetId === null) {
			throw(new \PDOException("not a valid favorite"));
		}

		// create query template
		$query = "INSERT INTO favorite(profileId, tweetId, favoriteDate) VALUES(:profileId, :tweetId, :favoriteDate)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->favoriteDate->format("Y-m-d H:i:s");
		$parameters = ["profileId" => $this->profileId, "tweetId" => $this->tweetId, "favoriteDate" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Favorite from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// ensure the object exists before deleting
		if($this->profileId === null || $this->tweetId === null) {
			throw(new \PDOException("not a valid favorite"));
		}

		// create query template
		$query = "DELETE FROM favorite WHERE profileId = :profileId AND tweetId = :tweetId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["profileId" => $this->profileId, "tweetId" => $this->tweetId];
		$statement->execute($parameters);
	}

	/**
	 * gets the Favorite by tweet id and profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $tweetId tweet id to search for
	 * @param int $profileId profile id to search for
	 * @return Favorite|null Favorite found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getFavoriteByTweetIdAndProfileId(\PDO $pdo, int $tweetId, int $profileId) {
		// sanitize the tweet id and profile id before searching
		if($tweetId <= 0) {
			throw(new \PDOException("tweet id is not positive"));
		}
		if($profileId < 0) {
			throw(new \PDOException("profile id is not positive"));
		}

		// create query template
		$query = "SELECT tweetId, profileId, favoriteDate FROM favorite WHERE tweetId = :tweetId AND profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the tweet id and profile id to the place holder in the template
		$parameters = ["tweetId" => $tweetId, "profileId" => $profileId];
		$statement->execute($parameters);

		// grab the favorite from mySQL
		try {
			$favorite = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$favorite = new Favorite($row["tweetId"], $row["profileId"], $row["favoriteDate"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($favorite);
	}

	/**
	 * gets the Favorite by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $profileId profile id to search for
	 * @return \SplFixedArray SplFixedArray of Favorites found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getFavoriteByProfileId(\PDO $pdo, int $profileId) {
		// sanitize the profile id
		if($profileId < 0) {
			throw(new \PDOException("profile id is not positive"));
		}

		// create query template
		$query = "SELECT profileId, tweetId, favoriteDate FROM favorite WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["profileId" => $profileId];
		$statement->execute($parameters);

		// build an array of favorites
		$favorites = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$favorite = new Favorite($row["tweetId"], $row["profileId"], $row["favoriteDate"]);
				$favorites[$favorites->key()] = $favorite;
				$favorites->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($favorites);
	}

	/**
	 * gets the Favorite by tweet it id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $tweetId tweet id to search for
	 * @return \SplFixedArray array of Favorites found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getFavoriteByTweetId(\PDO $pdo, int $tweetId) {
		// sanitize the tweet id
		$tweetId = filter_var($tweetId, FILTER_VALIDATE_INT);
		if($tweetId <= 0) {
			throw(new \PDOException("tweet id is not positive"));
		}

		// create query template
		$query = "SELECT profileId, tweetId, favoriteDate FROM favorite WHERE tweetId = :tweetId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["tweetId" => $tweetId];
		$statement->execute($parameters);

		// build an array of favorites
		$favorites = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$favorite = new Favorite($row["tweetId"], $row["profileId"], $row["favoriteDate"]);
				$favorites[$favorites->key()] = $favorite;
				$favorites->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($favorites);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["favoriteDate"] = intval($this->favoriteDate->format("U")) * 1000;
		return($fields);
	}
}
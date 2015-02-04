<?php
/**
 * Cross Section of a Twitter Favorite
 *
 * This is a cross section of what probably occurs when a user favorites a Tweet. It is an intersection table (weak
 * entity) from an m-to-n relationship between Profile and Tweet.
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class Favorite {
	/**
	 * id of the Tweet being favorited; this is a component of a composite primary key (and a foreign key)
	 **/
	private $tweetId;
	/**
	 * id of the Profile who favorited; this is a component of a composite primary key (and a foreign key)
	 **/
	private $profileId;
	/**
	 * date and time the tweet was favorited
	 **/
	private $favoriteDate;

	/**
	 * constructor for this Favorite
	 *
	 * @param int $newTweetId id of the parent Tweet
	 * @param int $newProfileId id of the parent Profile
	 * @param mixed $newFavoriteDate date the tweet was favorited (or null for current time)
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., negative integers)
	 **/
	public function __construct($newTweetId, $newProfileId, $newFavoriteDate = null) {
		// use the mutators to do the work for us!
		try {
			$this->setTweetId($newTweetId);
			$this->setProfileId($newProfileId);
			$this->setFavoriteDate($newFavoriteDate);
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
	 * @return int value of tweet id
	 **/
	public function getTweetId() {
		return($this->tweetId);
	}

	/**
	 * mutator method for tweet id
	 *
	 * @param int $newTweetId new value of tweet id
	 * @throws InvalidArgumentException if $newTweetId is not an integer
	 * @throws RangeException if $newTweetId is not positive
	 **/
	public function setTweetId($newTweetId) {
		// verify the tweet id is valid
		$newTweetId = filter_var($newTweetId, FILTER_VALIDATE_INT);
		if($newTweetId === false) {
			throw(new InvalidArgumentException("tweet id is not a valid integer"));
		}

		// verify the tweet id is positive
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
	 * accessor method for favorite date
	 *
	 * @return DateTime value of favorite date
	 **/
	public function getFavoriteDate() {
		return($this->favoriteDate);
	}

	/**
	 * mutator method for favorite date
	 *
	 * @param mixed $newFavoriteDate favorite date as a DateTime object or string (or null to load the current time)
	 * @throws InvalidArgumentException if $newFavoriteDate is not a valid object or string
	 * @throws RangeException if $newFavoriteDate is a date that does not exist
	 **/
	public function setFavoriteDate($newFavoriteDate) {
		// base case: if the date is null, use the current date and time
		if($newFavoriteDate === null) {
			$this->favoriteDate = new DateTime();
			return;
		}

		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newFavoriteDate) === true && get_class($newFavoriteDate) === "DateTime") {
			$this->favoriteDate = $newFavoriteDate;
			return;
		}

		// treat the date as a mySQL date string: Y-m-d H:i:s
		$newFavoriteDate = trim($newFavoriteDate);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newFavoriteDate, $matches)) !== 1) {
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
			throw(new RangeException("favorite date $newFavoriteDate is not a Gregorian date"));
		}

		// verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0  || $second > 60) {
			throw(new RangeException("favorite date $newFavoriteDate is not a valid time"));
		}

		// store the favorite date
		$newFavoriteDate = DateTime::createFromFormat("Y-m-d H:i:s", $newFavoriteDate);
		$this->favoriteDate = $newFavoriteDate;
	}
}
?>
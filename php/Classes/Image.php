<?php

namespace UssHopper\DataDesign;

require_once("autoload.php");
require_once(dirname(__DIR__) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Tweet image
 *
 * This Image class is an example of how twitter would handle images
 *
 * @author Marty Bonacci  <mbonacci@cnm.edu>
 * @modeled after Tweet.php by Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class Image implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for this Image; this is the primary key
	 * @var Uuid $imageId
	 **/
	private $imageId;
	/**
	 * id of the Tweet that this Image is part of; this is a foreign key
	 * @var Uuid $imageTweetId
	 **/
	private $imageTweetId;
	/**
	 * Cloudinary Token for this Image
	 * @var string $imageCloudinaryToken
	 **/
	private $imageCloudinaryToken;
	/**
	 * Cloudinary Url for this Image
	 * @var string $imageUrl
	 **/
	private $imageUrl;

	/**
	 * constructor for this Image
	 *
	 * @param string|Uuid $newImageId id of this Image or null if a new Image
	 * @param string|Uuid $newImageTweetId id of the Tweet that this Image is part of
	 * @param string $newImageCloudinaryToken string containing Cloudinary Token for this Image
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newImageId, $newImageTweetId, string $newImageCloudinaryToken, string $newImageUrl) {
		try {
			$this->setImageId($newImageId);
			$this->setImageTweetId($newImageTweetId);
			$this->setImageCloudinaryToken($newImageCloudinaryToken);
			$this->setImageUrl($newImageUrl);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for image id
	 *
	 * @return Uuid value of image id
	 **/
	public function getImageId(): Uuid {
		return ($this->imageId);
	}

	/**
	 * mutator method for image id
	 *
	 * @param Uuid/string $newImageId new value of image id
	 * @throws \TypeError if $newImageId is not a uuid or string
	 **/
	public function setImageId($newImageId): void {
		try {
			$uuid = self::validateUuid($newImageId);
		} catch(\InvalidArgumentException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the image id
		$this->imageId = $uuid;
	}

	/**
	 * accessor method for image tweet id
	 *
	 * @return Uuid value of image tweet id
	 **/
	public function getImageTweetId(): Uuid {
		return ($this->imageTweetId);
	}

	/**
	 * mutator method for image tweet id
	 *
	 * @param string | Uuid $newImageTweetId new value of image tweet id
	 * @throws \TypeError if $newImageTweetId is not a uuid or string
	 **/
	public function setImageTweetId($newImageTweetId): void {
		try {
			$uuid = self::validateUuid($newImageTweetId);
		} catch(\InvalidArgumentException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the profile id
		$this->imageTweetId = $uuid;
	}

	/**
	 * accessor method for image cloudinary token
	 *
	 * @return string value image cloudinary token
	 **/
	public function getImageCloudinaryToken(): string {
		return ($this->imageCloudinaryToken);
	}

	/**
	 * mutator method for image cloudinary token
	 *
	 * @param string $newImageCloudinaryToken new value of image cloudinary token
	 * @throws \InvalidArgumentException if $newImageCloudinaryToken is not a string or insecure
	 * @throws \TypeError if $newImageCloudinaryToken is not a string
	 **/
	public function setImageCloudinaryToken(string $newImageCloudinaryToken): void {
		// verify the image cloudinary token content is secure
		$newImageCloudinaryToken = filter_var($newImageCloudinaryToken, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newImageCloudinaryToken) === true) {
			throw(new \InvalidArgumentException("image cloudinary token is empty or insecure"));
		}

		// store the image cloudinary token
		$this->imageCloudinaryToken = $newImageCloudinaryToken;
	}

	/**
	 * accessor method for image url
	 *
	 * @return string value image url
	 **/
	public function getImageUrl(): string {
		return ($this->imageUrl);
	}

	/**
	 * mutator method for image url
	 *
	 * @param string $newImageUrl new value of image url
	 * @throws \InvalidArgumentException if $newImageUrl is not a string or insecure
	 * @throws \TypeError if $newImageUrl is not a string
	 **/
	public function setImageUrl(string $newImageUrl): void {
		// verify the image url content is secure
		$newImageUrl = filter_var($newImageUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newImageUrl) === true) {
			throw(new \InvalidArgumentException("image url is empty or insecure"));
		}

		// store the image url
		$this->imageUrl = $newImageUrl;
	}

	/**
	 * inserts this Image into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {

		// create query template
		$query = "INSERT INTO image(imageId, imageTweetId, imageCloudinaryToken, imageUrl) VALUES(:imageId, :imageTweetId, :imageCloudinaryToken, :imageUrl)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["imageId" => $this->imageId->getBytes(), "imageTweetId" => $this->imageTweetId->getBytes(), "imageCloudinaryToken" => $this->imageCloudinaryToken, "imageUrl" => $this->imageUrl];
		$statement->execute($parameters);
	}


	/**
	 * deletes this Image from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo): void {

		// create query template
		$query = "DELETE FROM image WHERE imageId = :imageId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["imageId" => $this->imageId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this Image in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {

		// create query template
		$query = "UPDATE image SET imageTweetId = :imageTweetId, imageCloudinaryToken = :imageCloudinaryToken, imageUrl = :imageUrl WHERE imageId = :imageId";
		$statement = $pdo->prepare($query);


		$parameters = ["imageId" => $this->imageId->getBytes(), "imageTweetId" => $this->imageTweetId->getBytes(), "imageCloudinaryToken" => $this->imageCloudinaryToken, "imageUrl" => $this->imageUrl];
		$statement->execute($parameters);
	}

	/**
	 * gets the Image by imageId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $imageId image id to search for
	 * @return Image|null Image found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable is not the correct data type
	 **/
	public static function getImageByImageId(\PDO $pdo, string $imageId): ?Image {
		// sanitize the imageId before searching
		try {
			$imageId = self::validateUuid($imageId);
		} catch(\InvalidArgumentException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT imageId, imageTweetId, imageCloudinaryToken, imageUrl FROM image WHERE imageId = :imageId";
		$statement = $pdo->prepare($query);

		// bind the image id to the place holder in the template
		$parameters = ["imageId" => $imageId->getBytes()];
		$statement->execute($parameters);

		// grab the image from mySQL
		try {
			$image = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$image = new Image($row["imageId"], $row["imageTweetId"], $row["imageCloudinaryToken"], $row["imageUrl"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($image);
	}

	/**
	 * gets the Image by Tweet id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $imageTweetId image tweet id to search by
	 * @return \SplFixedArray SplFixedArray of Images found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getImageByImageTweetId(\PDO $pdo, string $imageTweetId): \SPLFixedArray {

		try {
			$imageTweetId = self::validateUuid($imageTweetId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT imageId, imageTweetId, imageCloudinaryToken, imageUrl FROM image WHERE imageTweetId = :imageTweetId";
		$statement = $pdo->prepare($query);
		// bind the image tweet id to the place holder in the template
		$parameters = ["imageTweetId" => $imageTweetId->getBytes()];
		$statement->execute($parameters);
		// build an array of images
		$images = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$image = new Image($row["imageId"], $row["imageTweetId"], $row["imageCloudinaryToken"], $row["imageUrl"]);
				$images[$images->key()] = $image;
				$images->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($images);
	}

	public static function getImageByProfileId(\PDO $pdo, string $profileId): \SPLFixedArray {

		try {
			$profileId = self::validateUuid($profileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT image.imageId, image.imageTweetId, image.imageCloudinaryToken, image.imageUrl FROM image INNER JOIN tweet ON tweet.tweetId = image.imageTweetId WHERE tweet.tweetProfileId = :profileId";
		$statement = $pdo->prepare($query);
		// bind the image tweet id to the place holder in the template
		$parameters = ["profileId" => $profileId->getBytes()];
		$statement->execute($parameters);
		// build an array of images
		$images = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$image = new Image($row["imageId"], $row["imageTweetId"], $row["imageCloudinaryToken"], $row["imageUrl"]);
				$images[$images->key()] = $image;
				$images->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($images);
	}


	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["imageId"] = $this->imageId;
		$fields["imageTweetId"] = $this->imageTweetId;
		$fields["imageCloudinaryToken"] = $this->imageCloudinaryToken;
		$fields["imageUrl"] = $this->imageUrl;


		return ($fields);
	}
}

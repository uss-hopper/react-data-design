<?php
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
require_once(dirname(__DIR__) . "/Classes/autoload.php");
require("uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DataDesign\{Tweet, Profile};


$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");

$password = "abc123";
$hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);


////$tweet = new Tweet(generateUuidV4(), $profile->getProfileId(),"Let Them Eat Cake",  new \DateTime());
////$tweet->insert($pdo);
////
////$tweet1 = new Tweet(generateUuidV4(), $profile->getProfileId(), "Let them get loans", new \DateTime());
////$tweet1->insert($pdo);
////
////$tweet2 = new Tweet(generateUuidV4(), $profile->getProfileId(), "Let them eat cake and get loans", new \DateTime());
////$tweet2->insert($pdo);
////
////$tweet3 = new Tweet(generateUuidV4(), $profile->getProfileId(), "I like cake", new \DateTime());
////$tweet3->insert($pdo);


$tweets = Tweet::getTweetByTweetContent($pdo, "Cake");


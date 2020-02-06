<?php
require_once dirname(__DIR__, 1) . "/vendor/autoload.php";
require_once(dirname(__DIR__) . "/Classes/autoload.php");
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require("uuid.php");
$secrets = new \Secrets("/etc/apache2/capstone-mysql/ddctwitter.ini");
$pdo = $secrets->getPdoObject();


use UssHopper\DataDesign\{Tweet, Profile, Like, Image};

$password = "password";
$hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 45]);

$profile1 = new Profile(generateUuidV4(), null, "bonacciSequence", "https://www.fillmurray.com/128/128", "mbonacci@cnm.edu", $hash, "505-404-5678");
$profile1->insert($pdo);

$profile2 = new Profile(generateUuidV4(), null, "pschulzetenbe", "https://www.fillmurray.com/128/128", "pschulzetenber@cnm.edu", $hash, "505-888-5454");
$profile2->insert($pdo);

$profile3 = new Profile(generateUuidV4(), null, "BKie", "https://www.fillmurray.com/128/128", "bkie@cnm.edu", $hash, "505-866-5309");
$profile3->insert($pdo);

$profile4 = new Profile(generateUuidV4(), null, "janeNope", "https://www.fillmurray.com/128/128", "janeNope@cnm.edu", $hash, "505-877-3456");
$profile4->insert($pdo);

$tweet1 = new Tweet(generateUuidV4(), $profile1->getProfileId(),"IAgreeWeShouldUseCamelCase",  new \DateTime());
$tweet1->insert($pdo);


$tweet2 = new Tweet(generateUuidV4(), $profile1->getProfileId(),"I make custom Snow Boards",  new \DateTime());
$tweet2->insert($pdo);

$tweet3 = new Tweet(generateUuidV4(), $profile1->getProfileId(),"I try to hack react",  new \DateTime());
$tweet3->insert($pdo);

$tweet4 = new Tweet(generateUuidV4(), $profile1->getProfileId(),"I Like Big breakfast burritos and I cannot lie",  new \DateTime());
$tweet4->insert($pdo);

$tweet5 = new Tweet(generateUuidV4(), $profile2->getProfileId(),"Go Gophers",  new \DateTime());
$tweet5->insert($pdo);

$tweet6 = new Tweet(generateUuidV4(), $profile2->getProfileId(),"Minnesota is very cold this time of year.",  new \DateTime());
$tweet6->insert($pdo);

$tweet7 = new Tweet(generateUuidV4(), $profile2->getProfileId(),"Drupal > Wordpress",  new \DateTime());
$tweet7->insert($pdo);

$tweet8 = new Tweet(generateUuidV4(), $profile2->getProfileId(),"Catan is great but I prefer Forbidden Island",  new \DateTime());
$tweet8->insert($pdo);

$tweet9 = new Tweet(generateUuidV4(), $profile3->getProfileId(),"Hardcore musician Full Time Developer",  new \DateTime());
$tweet9->insert($pdo);

$tweet10 = new Tweet(generateUuidV4(), $profile3->getProfileId(),"DnB is where it's at",  new \DateTime());
$tweet10->insert($pdo);

$tweet11 = new Tweet(generateUuidV4(), $profile3->getProfileId(),"Past times include long bike rides",  new \DateTime());
$tweet11->insert($pdo);

$tweet12 = new Tweet(generateUuidV4(), $profile3->getProfileId(),"I think Marty Got lost again",  new \DateTime());
$tweet12->insert($pdo);

$like1 = new Like($profile1->getProfileId(), $tweet5->getTweetId());
$like1->insert($pdo);

echo "success1";

$like2 = new Like($profile1->getProfileId(), $tweet8->getTweetId());
$like2->insert($pdo);
echo "success2";
$like3 = new Like($profile1->getProfileId(), $tweet9->getTweetId());
$like3->insert($pdo);
echo "success3";
$like4 = new Like($profile2->getProfileId(), $tweet2->getTweetId());
$like4->insert($pdo);
echo "success4";
$like5 = new Like($profile2->getProfileId(), $tweet10->getTweetId());
$like5->insert($pdo);
echo "success5";
$like6 = new Like($profile3->getProfileId(), $tweet3->getTweetId());
$like6->insert($pdo);
echo "success6";
$like7 = new Like($profile3->getProfileId(), $tweet5->getTweetId());
$like7->insert($pdo);
echo "success7";
$like8 = new Like($profile2->getProfileId(), $tweet12->getTweetId());
$like8->insert($pdo);

$like9 = new Like($profile1->getProfileId(), $tweet12->getTweetId());
$like9->insert($pdo);
echo "success8";












<?php
require_once dirname(__DIR__, 1) . "/vendor/autoload.php";
require_once(dirname(__DIR__) . "/Classes/autoload.php");
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require("uuid.php");
$secrets = new \Secrets("/etc/apache2/capstone-mysql/ddctwitter.ini");
$pdo = $secrets->getPdoObject();


use UssHopper\DataDesign\Tweet;

$tweets = Tweet::getTweetProfilesByTweetProfileId($pdo, "319b69ad-4f08-43f4-b4a8-5f91fe304ad6");
var_dump($tweets);
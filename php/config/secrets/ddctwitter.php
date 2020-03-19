<?php
/**
 * This file should live outside of the project and be manually added to the server
 *
 **/

require_once("Secrets.php");
$config = [];
$api = new stdClass();
$api->randomKey = "1234567890";
$api->anotherRandomKey = "abcdefghijklmnopqrstuvwxyz";
$config["api"] = json_encode($api);
//make sure this path matches the ini file path you have been using for Capstone.
$hideSecrets = new \Secrets("/etc/apache2/capstone-mysql/ddctwitter.ini");
$hideSecrets->setSecrets($config);




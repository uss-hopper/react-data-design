<?php
/**
 * This file should live outside of the project and be manually add to the server
 *
 **/

require_once("/var/www/secrets/Secrets.php");
$config = [];
$api = new stdClass();
$api->randomKey = "1234567890";
$api->anotherRandomKey = "abcdefghijklmnopqrstuvwxyz";
$config["api"] = json_encode($api);
$hideSecrets = new \Secrets("/var/www/secrets/ddctwitter.ini");
$hideSecrets->setSecrets($config);




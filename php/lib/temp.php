<?php

require_once dirname(__DIR__, 1) . "/vendor/autoload.php";

use Faker\Generator;
$faker =  new Generator();
$fakerIpsum =

// generate data by accessing properties
echo $faker->loren;
// 'Lucy Cechtelar';
echo $faker->address;
// "426 Jordy Lodge
// Cartwrightshire, SC 88120-6700"
echo $faker->text;

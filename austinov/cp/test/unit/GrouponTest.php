<?php

require_once dirname(__FILE__).'/../bootstrap/unit.php';

// Create symfony instance to load the configuration
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
sfContext::createInstance($configuration);

//use \DealsProvider\Groupon as Groupon;

$groupon = new Groupon();

/*
if ($argc < 4) // first argument is the file name, but we need 3 more important arguments
    exit("Not enough arguments: lat, long, radius [,type] are required \n");

$lat = $argv[1];
$long = $argv[2];
$radius = $argv[3];
$type = isset($argv[4]) ? $argv[4] : null;
*/
/* Denver, US */
$lat = 39.744170;
$long = -104.9905600;
$radius = 2000;

// Init tester
$t = new lime_test(1);
$t->comment('Testing Groupon provider');

$response = $groupon->getDeals($lat, $long, $radius);
$t->plan++;
$t->ok(isset($response['deals']), 'Response is not empty');

//print_r($response);
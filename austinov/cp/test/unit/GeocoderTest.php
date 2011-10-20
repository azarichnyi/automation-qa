<?php

require_once dirname(__FILE__).'/../bootstrap/unit.php';

$geocoder = new Geocoder();

// with precise address
$lat = 50.38655553;
$lon = 30.45840275;

// without precise address - only street name witout house number
//$lat = 50.370923;
//$lon = 30.494392;

$t = new lime_test(2);
$t->comment('Testing reverse geocoding');
$locationName = $geocoder->getLocationName($lat, $lon);
$t->ok($geocoder->getLocationName($lat, $lon), 'Geocoder returns not empty response');
$t->is($geocoder->getLocationName($lat, $lon), "Akademika Vil'yamsa St, 13, Kiev, Kyiv city, Ukraine",  'Geocoder returns correct location name');
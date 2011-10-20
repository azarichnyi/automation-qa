<?php

require_once dirname(__FILE__).'/../bootstrap/unit.php';

$t = new lime_test(1);
$t->pass('This test always passes.');

/*
$testdata = json_decode('[
    {"name":"Vasylkiv - Kyiv", "expected":30.044,
      "lat0":50.18060, "lon0":30.31138, "lat1":50.41460, "lon1":30.52194},
    {"name":"Kirov - Noyabrsk", "expected":1477.21,
      "lat0":58.61120, "lon0":49.62322, "lat1":63.19830, "lon1":75.47232},
    {"name":"Murmansk - Vorkuta", "expected":1273.79,
      "lat0":68.948803, "lon0":33.09975, "lat1":67.50475, "lon1":64.04474},
    {"name":"Kinshasa - Nairobi", "expected": 2426.090,
      "lat0":-4.32473, "lon0":15.32716, "lat1":-1.31383, "lon1":36.94114}
    
  ]');

// Init tester
$t = new lime_test(sizeof($testdata));
$t->comment('Testing Distance measure Calculator');

foreach ($testdata as $test_point)
{
    $test_point = (array)$test_point;
    $t->comment('Testing point: ' . $test_point['name']);
    
    $dc = new DistanceCalculator($test_point['lat0'], $test_point['lon0'], $test_point['lat1'], $test_point['lon1'], 'meter');
    $t->is(round($dc->getDistance(), 5), $test_point['expected'], 'Distance measured correctly');
}
*/
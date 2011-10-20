<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';
require_once dirname(__FILE__).'/model/LastTileTest.class.php';


cpMemcache::getInstance()->delete('tiles1');

$user = new User();
$user->setId(1);

$lastTile = new LastTileTest($user);


$t = new lime_test(2);
$t->comment('Test empty memcache and empty array of last tile');
$temp = cpMemcache::getInstance()->get('tiles1');
$t->is($temp, false);
$temp = $lastTile->getTiles();
$t->is(count($temp), 0);


$t = new lime_test(6);
$t->comment('Adding new point');
$lastTile->addTile('12345', '1', '2011-07-05 15:00:00');
$temp = $lastTile->getTiles();
$t->is(count($temp), 1);
$first = $temp['12345.1'];

$t->is(is_array($first), true);
$t->is($first['received'], '2011-07-05 15:00:00');
$t->is($first['road'], 12345);
$t->is($first['tile'], 1);
$t->is($first['direction'], 0);

// ------------------------------------------------------------------------------
$t = new lime_test(1);
$t->comment('Adding 1 *same* point');
$lastTile->addTile('12345', '1', '2011-07-05 15:00:05');
$temp = $lastTile->getTiles();
$t->is(count($temp), 1);

// ------------------------------------------------------------------------------
$t = new lime_test(9);
$t->comment('Adding 2 *new* point');
$lastTile->addTile('12345', '2', '2011-07-05 15:00:10');
$temp = $lastTile->getTiles();
$t->is(count($temp), 2);
$first = $temp['12345.1'];
$second = $temp['12345.2'];

$t->is($first['received'], '2011-07-05 15:00:00');
$t->is($first['road'], 12345);
$t->is($first['tile'], 1);
$t->is($first['direction'], 0);

$t->is($second['received'], '2011-07-05 15:00:10');
$t->is($second['road'], 12345);
$t->is($second['tile'], 2);
$t->is($second['direction'], 2);

// ------------------------------------------------------------------------------
$t = new lime_test(9);
$t->comment('Adding 3 *one of the previous* point');
$lastTile->addTile('12345', '1', '2011-07-05 15:00:15');
$temp = $lastTile->getTiles();
$t->is(count($temp), 2);
$first = $temp['12345.1'];
$second = $temp['12345.2'];

$t->is($first['received'], '2011-07-05 15:00:00');
$t->is($first['road'], 12345);
$t->is($first['tile'], 1);
$t->is($first['direction'], 0);

$t->is($second['received'], '2011-07-05 15:00:10');
$t->is($second['road'], 12345);
$t->is($second['tile'], 2);
$t->is($second['direction'], 2);

// ------------------------------------------------------------------------------
$t = new lime_test(9);
$t->comment('Adding 4 *new* point');
$lastTile->addTile('12345', '3', '2011-07-05 15:00:20');
$temp = $lastTile->getTiles();
$t->is(count($temp), 2);
$first = $temp['12345.2'];
$second = $temp['12345.3'];

$t->is($first['received'], '2011-07-05 15:00:10');
$t->is($first['road'], 12345);
$t->is($first['tile'], 2);
$t->is($first['direction'], 2);

$t->is($second['received'], '2011-07-05 15:00:20');
$t->is($second['road'], 12345);
$t->is($second['tile'], 3);
$t->is($second['direction'], 2);

// ------------------------------------------------------------------------------
$t = new lime_test(9);
$t->comment('Adding 5 *new* point (direction change)');
$lastTile->addTile('12345', '1', '2011-07-05 15:00:25');
$temp = $lastTile->getTiles();
$t->is(count($temp), 2);
$first = $temp['12345.3'];
$second = $temp['12345.1'];

$t->is($first['received'], '2011-07-05 15:00:20');
$t->is($first['road'], 12345);
$t->is($first['tile'], 3);
$t->is($first['direction'], 2);

$t->is($second['received'], '2011-07-05 15:00:25');
$t->is($second['road'], 12345);
$t->is($second['tile'], 1);
$t->is($second['direction'], 1);


// ------------------------------------------------------------------------------
$t = new lime_test(9);
$t->comment('Adding 6 *new* point (direction change)');
$lastTile->addTile('12345', '1', '2011-07-05 15:00:25');
$temp = $lastTile->getTiles();
$t->is(count($temp), 2);
$first = $temp['12345.3'];
$second = $temp['12345.1'];

$t->is($first['received'], '2011-07-05 15:00:20');
$t->is($first['road'], 12345);
$t->is($first['tile'], 3);
$t->is($first['direction'], 2);

$t->is($second['received'], '2011-07-05 15:00:25');
$t->is($second['road'], 12345);
$t->is($second['tile'], 1);
$t->is($second['direction'], 1);

// ------------------------------------------------------------------------------
$t = new lime_test(9);
$t->comment('Adding 7 *new* point (on new road)');
$lastTile->addTile('123456', '1', '2011-07-05 15:00:30');
$temp = $lastTile->getTiles();
$t->is(count($temp), 2);
$first = $temp['12345.1'];
$second = $temp['123456.1'];

$t->is($first['received'], '2011-07-05 15:00:25');
$t->is($first['road'], 12345);
$t->is($first['tile'], 1);
$t->is($first['direction'], 1);

$t->is($second['received'], '2011-07-05 15:00:30');
$t->is($second['road'], 123456);
$t->is($second['tile'], 1);
$t->is($second['direction'], 0);

// ------------------------------------------------------------------------------
$t = new lime_test(9);
$t->comment('Adding 8 *new* point');
$lastTile->addTile('123456', '2', '2011-07-05 15:00:35');
$temp = $lastTile->getTiles();
$t->is(count($temp), 2);
$first = $temp['123456.1'];
$second = $temp['123456.2'];

$t->is($first['received'], '2011-07-05 15:00:30');
$t->is($first['road'], 123456);
$t->is($first['tile'], 1);
$t->is($first['direction'], 0);

$t->is($second['received'], '2011-07-05 15:00:35');
$t->is($second['road'], 123456);
$t->is($second['tile'], 2);
$t->is($second['direction'], 2);


$t = new lime_test(3);
$t->comment('Testing directions');
$t->is($lastTile->getAccumulateDirection(123456, 1), 1);
$t->is($lastTile->getAccumulateDirection(123456, 2), 2);
$t->is($lastTile->getAccumulateDirection(123456, 3), 2);





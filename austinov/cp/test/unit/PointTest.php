<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';
require_once dirname(__FILE__).'/model/PointTest.class.php';



// isValid --------------------------------------------------------------
$t = new lime_test(1);
$t->comment('isValid: Received - ok');
$point = new PointTest();
$point->setReceived('2011-07-20 10:10:10 +0000');
$t->is($point->isInputValid(), true);

$t = new lime_test(1);
$t->comment('isValid: Received - +2 ');
$point = new PointTest();
$point->setReceived('2011-07-20 10:10:10 +0002');
$t->is($point->isInputValid(), true);

$t = new lime_test(1);
$t->comment('isValid: Received - without time zone');
$point = new PointTest();
$point->setReceived('2011-07-20 10:10:10');
$t->is($point->isInputValid(), false);

$t = new lime_test(1);
$t->comment('isValid: Received - incorrect date');
$point = new PointTest();
$point->setReceived('incorrect date');
$t->is($point->isInputValid(), false);

$t = new lime_test(1);
$t->comment('isValid: Received - incorrect date 2');
$point = new PointTest();
$point->setReceived('2011-17-40 10:10:10 +0000');
$t->is($point->isInputValid(), false);

$t = new lime_test(1);
$t->comment('isValid: SpeedGps - Normal');
$point = new PointTest();
$point->setReceived('2011-07-04 10:10:10 +0000');
$point->setSpeedGps(10);
$t->is($point->isInputValid(), true);

$t = new lime_test(1);
$t->comment('isValid: SpeedGps - negative');
$point = new PointTest();
$point->setReceived('2011-07-04 10:10:10 +0000');
$point->setSpeedGps(-10);
$t->is($point->isInputValid(), false);

$t = new lime_test(1);
$t->comment('isValid: SpeedGps - to big');
$point = new PointTest();
$point->setReceived('2011-07-04 10:10:10 +0000');
$point->setSpeedGps(2000);
$t->is($point->isInputValid(), false);


// creditPB ------------------------------------------------------------------

$t = new lime_test(1);
$t->comment('creditPB');
$point = new PointTest();
$point->setReceived('2011-07-04 10:10:10 +0000');
$point->setLat(50.4001);
$point->setLon(30.6459);
$point->setSpeedGps(20);
$point->setDirection(90);
$point->creditPB();
$t->isnt($point->getPainBucks(), null);

// setRoadInfo ----------------------------------------------------------------
$t = new lime_test(4);
$t->comment('setRoadInfo Bazhana');

$point = new PointTest();
$point->setReceived('2011-07-04 10:10:10 +0000');
$point->setLat(50.4001);
$point->setLon(30.6459);
$point->setSpeedGps(20);
$point->setDirection(90);
$point->setRoadInfo();
$t->is($point->getRoadOsmId(), 70017184);
$t->is($point->getRoadType(), 'trunk');
$t->is($point->getRoadTile(), 8);
$t->is($point->getCityOsmId(), 26150422);

$t = new lime_test(4);
$t->comment('setRoadInfo Outside road neer Kiev');

$point = new PointTest();
$point->setReceived('2011-07-04 10:10:10 +0000');
$point->setLat(50.369719);
$point->setLon(30.649352);
$point->setSpeedGps(20);
$point->setDirection(90);
$point->setRoadInfo();
$t->is($point->getRoadOsmId(), '');
$t->is($point->getRoadType(), '');
$t->is($point->getRoadTile(), '');
$t->is($point->getCityOsmId(), 26150422);







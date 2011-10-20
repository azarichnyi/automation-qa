<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/unit');

$point1 = new Point();
$point1->setUserId(2);
$point1->setLat(50.39006);
$point1->setLon(30.46452);
$point1->setCityOsmId(26150422);
$point1->setRoadOsmId(31727716);
$point1->setRoadTile(26);
$point1->setRoadType("secondary");
$point1->setSpeedGps(75.60);
$point1->setDirection(229);
$point1->setDirectionAcc(2);
$point1->setReceived('2011-08-09 12:08:30 +0000');

$point2 = new Point();
$point2->setUserId(2);
$point2->setLat(50.39018);
$point2->setLon(30.46475);
$point2->setCityOsmId(26150422);
$point2->setRoadOsmId(31727716);
$point2->setRoadTile(27);
$point2->setRoadType("secondary");
$point2->setSpeedGps(74.70);
$point2->setDirection(229);
$point2->setDirectionAcc(2);
$point2->setReceived('2011-08-09 12:08:32 +0000');

$point3 = new Point();
$point3->setUserId(2);
$point3->setLat(50.39030);
$point3->setLon(30.46497);
$point3->setCityOsmId(26150422);
$point3->setRoadOsmId(31727716);
$point3->setRoadTile(27);
$point3->setRoadType("secondary");
$point3->setSpeedGps(73.80);
$point3->setDirection(229);
$point3->setDirectionAcc(2);
$point3->setReceived('2011-08-09 12:08:35 +0000');



// saveAll ----------------------------------------------------------------------

$userList[] = $point1;
$userList[] = $point2;
$userList[] = $point3;
$res = Doctrine_Core::getTable('Point')->saveAll($userList);


$points = Doctrine_Core::getTable('Point')->findByUserId(2);

$temp = $points->getData();

$res1 = $temp[0]; 
$res2 = $temp[1]; 
$res3 = $temp[2]; 

$t = new lime_test(34);
$t->comment('saveAll');
$t->is(count($points), 3);

$t->is($res1->getUserId(), 2);
$t->is($res1->getLat(), 50.39006);
$t->is($res1->getLon(), 30.46452);
$t->is($res1->getCityOsmId(), 26150422);
$t->is($res1->getRoadOsmId(), 31727716);
$t->is($res1->getRoadTile(), 26);
$t->is($res1->getRoadType(), "secondary");
$t->is($res1->getSpeedGps(), 75.60);
$t->is($res1->getDirection(), 229);
$t->is($res1->getDirectionAcc(), 2);
$t->is($res1->getReceived(), '2011-08-09 12:08:30');


$t->is($res2->getUserId(), 2);
$t->is($res2->getLat(), 50.39018);
$t->is($res2->getLon(), 30.46475);
$t->is($res2->getCityOsmId(), 26150422);
$t->is($res2->getRoadOsmId(), 31727716);
$t->is($res2->getRoadTile(), 27);
$t->is($res2->getRoadType(), "secondary");
$t->is($res2->getSpeedGps(), 74.70);
$t->is($res2->getDirection(), 229);
$t->is($res2->getDirectionAcc(), 2);
$t->is($res2->getReceived(), '2011-08-09 12:08:32');


$t->is($res3->getUserId(), 2);
$t->is($res3->getLat(), 50.39030);
$t->is($res3->getLon(), 30.46497);
$t->is($res3->getCityOsmId(), 26150422);
$t->is($res3->getRoadOsmId(), 31727716);
$t->is($res3->getRoadTile(), 27);
$t->is($res3->getRoadType(), "secondary");
$t->is($res3->getSpeedGps(), 73.80);
$t->is($res3->getDirection(), 229);
$t->is($res3->getDirectionAcc(), 2);
$t->is($res3->getReceived(), '2011-08-09 12:08:35');
 
<?php


include(dirname(__FILE__).'/../bootstrap/Doctrine.php');

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/average/AverageRoad1.yml');
$configuration = ProjectConfiguration::getActive(); 

$task = new CalculateAllTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array(), array());

// -- AverageRoad1 -------------------------------------------------------------------------------

$t = new lime_test(3);
$t->comment('AverageRoad1: direction 1');

$query = Doctrine_Query::create()
    ->from('AverageRoad')
    ->addWhere('road_osm_id = ?', 28878127)
    ->addWhere('city_osm_id = ?', 26150422)
    ->addWhere('direction = ?', 1);
$res = $query->execute();
$average_road = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average_road->getSpeed(), 1), 48.4);
$t->is(round($average_road->getDeviation(), 2), 6.04);


$t = new lime_test(3);
$t->comment('AverageRoad1: direction 2');

$query = Doctrine_Query::create()
    ->from('AverageRoad')
    ->addWhere('road_osm_id = ?', 28878127)
    ->addWhere('city_osm_id = ?', 26150422)
    ->addWhere('direction = ?', 2);
$res = $query->execute();
$average_road = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average_road->getSpeed(), 1), 50);
$t->is(round($average_road->getDeviation(), 2), 4);


// -- AverageRoad2 -------------------------------------------------------------------------------

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/average/AverageRoad2.yml');

$configuration = ProjectConfiguration::getActive(); 

$task = new CalculateAllTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array(), array());

$t = new lime_test(3);
$t->comment('AverageRoad2');

$query = Doctrine_Query::create()
    ->from('AverageRoad')
    ->addWhere('road_osm_id = ?', 26532986)
    ->addWhere('city_osm_id = ?', 26150437);
$res = $query->execute();
$average_road = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average_road->getSpeed(), 2), 52.38);
$t->is(round($average_road->getDeviation(), 2), 6.87);
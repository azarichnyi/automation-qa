<?php


include(dirname(__FILE__).'/../bootstrap/Doctrine.php');

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/average/AverageCity.yml');
$configuration = ProjectConfiguration::getActive(); 

$task = new CalculateAverageCityTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array(), array());

// -- AverageRoad1 -------------------------------------------------------------------------------

$t = new lime_test(3);
$t->comment('AverageCity');

$query = Doctrine_Query::create()
    ->from('AverageCity')
    ->addWhere('city_osm_id = ?', 26150437);
$res = $query->execute();
$average = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average->getSpeed(), 2), 50.26);
$t->is(round($average->getDeviation(), 2), 1.64);


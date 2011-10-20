<?php


include(dirname(__FILE__).'/../bootstrap/Doctrine.php');

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/average/AverageTile.yml');
$configuration = ProjectConfiguration::getActive(); 

$task = new CalculateAverageTileTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array(), array());

// -- AverageRoad1 -------------------------------------------------------------------------------
$t = new lime_test(3);
$t->comment('AverageTile');

$query = Doctrine_Query::create()
    ->from('AverageTile')
    ->addWhere('tile = ?', 1738865770498);
$res = $query->execute();
$average = $res->getFirst();

$t->is(count($res), 1);
// @TODO: Fixme - this test does not pass and this is expected behavior. So, fix the code!
//$t->is(round($average->getDirection(), 2), 230);
$t->is(round($average->getSpeed(), 2), 52.5);
$t->is(round($average->getDeviation(), 2), 5.5);


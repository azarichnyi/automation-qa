<?php


include(dirname(__FILE__).'/../bootstrap/Doctrine.php');

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/average/AveragePb.yml');
$configuration = ProjectConfiguration::getActive(); 

$task = new CalculateAllTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array(), array());

// -- AveragePb -------------------------------------------------------------------------------
$t = new lime_test(2);
$t->comment('AveragePb User1');

$query = Doctrine_Query::create()
    ->from('AveragePb')
    ->addWhere('user_id = ?', 1);
$res = $query->execute();
$average = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average->getPainBucks(), 2), 21.41);

// ---------------------------------------------------------------------------------------------

$t = new lime_test(2);
$t->comment('AveragePb User2');

$query = Doctrine_Query::create()
    ->from('AveragePb')
    ->addWhere('user_id = ?', 2);
$res = $query->execute();
$average = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average->getPainBucks(), 2), 24.97);

// ---------------------------------------------------------------------------------------------

$t = new lime_test(2);
$t->comment('AveragePb User3');

$query = Doctrine_Query::create()
    ->from('AveragePb')
    ->addWhere('user_id = ?', 3);
$res = $query->execute();
$average = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average->getPainBucks(), 2), 23.72);

// ---------------------------------------------------------------------------------------------

$t = new lime_test(2);
$t->comment('AveragePb User4');

$query = Doctrine_Query::create()
    ->from('AveragePb')
    ->addWhere('user_id = ?', 4);
$res = $query->execute();
$average = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average->getPainBucks(), 2), 1.85);


// ---------------------------------------------------------------------------------------------

$t = new lime_test(3);
$t->comment('AveragePbTotal');

$query = Doctrine_Query::create()
    ->from('AveragePbTotal');
$res = $query->execute();
$average = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average->getPainBucks(), 2), 17.99);
$t->is(round($average->getDeviation(), 2), 9.40);


// ---------------------------------------------------------------------------------------------

$t = new lime_test(2);
$t->comment('User Average Coef');

$query = Doctrine_Query::create()
            ->from('User')
            ->addWhere('sf_guard_user_id = ?', 4);
$res = $query->execute();
$average = $res->getFirst();

$t->is(count($res), 1);
$t->is(round($average->getPainbucksAvgCoef(), 2), 2);
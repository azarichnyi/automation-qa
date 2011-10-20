<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

cpMemcache::getInstance()->delete('tiles1');
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/avgpb0');
$browser = new sfTestFunctional(new sfBrowser());

$browser->info('Test checks Average Pain Bucks Calculation');

//dump(shell_exec(
//     sfConfig::get('sf_root_dir').'/symfony GroupUserTracks --env=tost'));

$browser->info('> Running GroupUserTracks Task');
$cnfg = ProjectConfiguration::getActive(); 
$task = new GroupUserTracksTask($cnfg->getEventDispatcher(),
                                          new sfFormatter());
$task->run(array(), array());

$q = Doctrine::getTable('AveragePb')->createQuery('q');
$avg = $q->fetchArray();

$browser->info('> Testing table contents');
$browser->test()->is($avg[0]['painbucks'], 21.4120, 'Cash amount for user 1');
$browser->test()->is($avg[1]['painbucks'], 24.9680, 'Cash amount for user 2');
$browser->test()->is($avg[2]['painbucks'], 23.72, 'Cash amount for user 3');
$browser->test()->is($avg[3]['painbucks'], 1.85, 'Cash amount for urer 4');

$q = Doctrine::getTable('AveragePbTotal')->createQuery('q');
$avgtot = $q->fetchArray();
$browser->info('> Testing average_pb_total');
$browser->test()->is($avgtot[0]['painbucks'], 17.9875, 'Avg total PB');
$browser->test()->is($avgtot[0]['deviation'], 9.4039, 'Avg total deviation');

$q = Doctrine::getTable('User')->
               createQuery('q')->where('q.sf_guard_user_id=?', 4);
$usrs = $q->fetchArray();
$browser->info('> > Testing users coeff');
$browser->test()->is($usrs[0]['painbucks_avg_coef'], 2.0, 'Coeff for user 4');

<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

cpMemcache::getInstance()->delete('tiles1');
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/user0');
$browser = new sfTestFunctional(new sfBrowser());
$td = sfConfig::get('sf_test_dir').'/testdata/';

$user = UserTable::getInstance()->findAll()->toArray();
$user = reset($user);
// Clear all mess stored in redis from any previous tests
$rclient = sfRedis::getClient();
$rclient->delete('track:' . $user['sf_guard_user_id']);

$browser->info('Test verifies tracks calculation and grouping')->
  info('  Logging In...')->
  post('/user/login', array( 'auth_info' =>
                             '{"email":"johndoe","password":"123456"}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  with('user')->isAuthenticated()->
  
  info('  Posting first track ss_trk_01.json')->
  post('/track/add', array('points' =>
                                  file_get_contents($td.'ss_trk_01.json')))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Posting second track ss_trk_02.json')->
  post('/track/add', array('points'=>
                                  file_get_contents($td.'ss_trk_02.json')))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Posting third track ss_trk_03.json')->
  post('/track/add', array('points' =>
                                  file_get_contents($td.'ss_trk_03.json')))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Posting fourth track ss_trk_04.json')->
  post('/track/add', array('points' =>
                                  file_get_contents($td.'ss_trk_04.json')))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Posting fifth track ss_trk_05.json')->
  post('/track/add', array('points' =>
                                  file_get_contents($td.'ss_trk_05.json')))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Posting sixth track ss_trk_06.json')->
  post('/track/add', array('points' =>
                                  file_get_contents($td.'ss_trk_06.json')))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Posting seventh track ss_trk_07.json')->
  post('/track/add', array('points' =>
                                  file_get_contents($td.'ss_trk_07.json')))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Posting eigth track ss_trk_08.json')->
  post('/track/add', array('points' =>
                                  file_get_contents($td.'ss_trk_08.json')))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Posting ningth track ss_trk_09.json')->
  post('/track/add', array('points' =>
                                  file_get_contents($td.'ss_trk_09.json')))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()
;

$browser->info('Checking the database entries');
$q = Doctrine::getTable('Track')->createQuery('t')->where('t.user_id = ?', 1);
$tracks = $q->fetchArray();

$browser->info('  Checking if stop categories are as expected');
$browser->test()->is($tracks[0]['end_stop_category'],'5m','5m');
$browser->test()->is($tracks[1]['begin_stop_category'],'5m','5m');
$browser->test()->is($tracks[1]['end_stop_category'],'15m','15m');
$browser->test()->is($tracks[2]['begin_stop_category'],'15m','15m');
$browser->test()->is($tracks[2]['end_stop_category'],'30m','30m');
$browser->test()->is($tracks[3]['begin_stop_category'],'30m','30m');
$browser->test()->is($tracks[3]['end_stop_category'],'1h','1h');
$browser->test()->is($tracks[4]['begin_stop_category'],'1h','1h');
$browser->test()->is($tracks[4]['end_stop_category'],'4h','4h');
$browser->test()->is($tracks[5]['begin_stop_category'],'4h','4h');
$browser->test()->is($tracks[5]['end_stop_category'],'8h','8h');
$browser->test()->is($tracks[6]['begin_stop_category'],'8h','8h');
$browser->test()->is($tracks[6]['end_stop_category'],'12h','12h');
$browser->test()->is($tracks[7]['begin_stop_category'],'12h','12h');
$browser->test()->is($tracks[7]['end_stop_category'],'long','long');

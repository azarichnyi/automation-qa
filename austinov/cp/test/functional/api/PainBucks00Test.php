<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

cpMemcache::getInstance()->delete('tiles1');
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/smoke0');
$browser = new sfTestFunctional(new sfBrowser());

$browser->info('Testing Painbucks Charging for all the algos')-> 
  info('  Logging in...')->
  post('/user/login', array( 'auth_info' =>
                             '{"email":"johndoe","password":"123456"}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Posting two points into the tile')->
  post('/track/add', array( 'points' => '{"locations": [{
    "lat": 50.61848,
    "lon": 26.25716,
    "speed": 72,
    "timestamp": "2012-07-14 14:12:24 +0000",
    "direction": 0
    },{
    "lat": 50.61853,
    "lon": 26.25678,
    "speed": 80,
    "timestamp": "2012-07-14 14:12:28 +0000",
    "direction": 0} ]}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/dd":0\.01,/')->
    matches('/dc":0\.01,/')->
    matches('/cc":0\.01,/')->
    matches('/ib":0\.01}/')->
  end()
;

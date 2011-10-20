<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

cpMemcache::getInstance()->delete('tiles1');
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/pbctrb');
$browser = new sfTestFunctional(new sfBrowser());

$browser->info('Testing Contribution Painbucks')-> 
  info('  Logging in as johndoe')->
  post('/user/login', array( 'auth_info' =>
                             '{"email":"johndoe","password":"123456"}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success":true/')->
  end()->
  
  info('  Posting point into the tile')->
  post('/track/add', array( 'points' => '{"locations": [ {
    "lat": 50.41477,
    "lon": 30.51988,
    "speed": 10,
    "timestamp": "2011-04-20 18:07:31 +0000"
  } ]}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/dd":0,/')->
    matches('/dc":0,/')->
    matches('/cc":0,/')->
    matches('/ib":0\.01}/')->
  end()->
  
  info('  One more point in the same tile but with 20 kmh speed')->
  post('/track/add', array( 'points' => '{"locations": [ {
    "lat": 50.41477,
    "lon": 30.51988,
    "speed": 20,
    "timestamp": "2011-04-22 18:07:31 +0000"
  } ]}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/dd":0,/')->
    matches('/dc":0,/')->
    matches('/cc":0,/')->
    matches('/ib":0\.01}/')->
  end()->
  
  info('  Third point into the same tile but with 50 kmh speed')->
  post('/track/add', array( 'points' => '{"locations": [ {
    "lat": 50.41477,
    "lon": 30.51988,
    "speed": 50,
    "timestamp": "2011-04-25 18:07:31 +0000"
  } ]}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/dd":0,/')->
    matches('/dc":0,/')->
    matches('/cc":0,/')->
    matches('/ib":0\.01}/')->
  end()->
  
  info('  New point in the tile with time matching average')->
  post('/track/add', array('points' => '{"locations": [ {
    "lat": 50.41477,
    "lon": 30.51988,
    "speed": 13,
    "timestamp": "2011-07-05 00:07:31 +0000"
  }]}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/dd":0,/')->
    matches('/dc":0,/')->
    matches('/cc":0,/')->
    matches('/ib":0\.01}/')->
  end()
;

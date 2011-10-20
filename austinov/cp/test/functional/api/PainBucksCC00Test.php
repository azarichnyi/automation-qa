<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

cpMemcache::getInstance()->delete('tiles1');
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/pbcc0');
$browser = new sfTestFunctional(new sfBrowser());

$browser->info('Testing Community vs Community')->
  post('/user/login', array( 'auth_info' =>
                             '{"email":"johndoe","password":"123456"}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  with('user')->isAuthenticated()->
  
  info('  Posting point inte the tile')->
  post('/track/add', array( 'points' => '{"locations": [ {
    "lat": 50.41755,
    "lon": 30.52017,
    "speed": 40,
    "timestamp": "2012-07-01 00:07:31 +0000",
    "direction": 0
    },{
    "lat": 50.41667,
    "lon": 30.52075,
    "speed": 40,
    "timestamp": "2012-07-01 00:07:51 +0000",
    "direction": 0
  } ]}'))->
  
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/dd":0,/')->
    matches('/dc":0,/')->
    matches('/cc":0\.02,/')->
    matches('/ib":0\.02}/')->
  end()
;

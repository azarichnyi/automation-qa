<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

Doctrine_Core ::loadData(sfConfig::get('sf_test_dir').'/fixtures/user0');
$browser = new sfTestFunctional(new sfBrowser());
$friends = array(
  0 => array("name" => "Friend One", "email" => "austinov@cogniance.com"),
  1 => array("name" => "Friend Two", "email" => "rdushko@cogniance.com")
);

var_dump(json_encode($friends));

$browser->info('Test perform various commute actions')->
  info('  Logging In...')->
  post('/user/login', array('auth_info' => '{"email":"johndoe","password":"123456"}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  with('user')->isAuthenticated()->
  info('Reccomending a friend')->
  post('/user/recommendFriend', array('recommendation' => json_encode($friends)))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end();

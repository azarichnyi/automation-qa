<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

Doctrine_Core ::loadData(sfConfig::get('sf_test_dir').'/fixtures/buser0');
$browser = new sfTestFunctional(new sfBrowser());
$venue = array(
  "name" => "Test Venue",
  "lat" => "50",
  "lon" => "30",
  "address" => "Test Address",
  "phone" => "Test Phone");
$deal = array(
  "name" => "Test Deal",
  "venue_id" => "Test Venue",
  "category_id" => 4,
  "description" => "Test Deal's Description",
  "start_date" => "2011-09-09",
  "expiration_date" => "2011-11-11",
  "price" => 1,
  "total_count" => 1,
  "discount_price" => 1,
  "discount_cc" => 1
  );
$signin_url = sfConfig::get('sf_login_module','default').'/'.sfConfig::get('sf_login_action','default');
var_dump($signin_url);

$browser->info('Business Home Page')->
  get('venue/add')->
    with('response')->isStatusCode(401)->
  post($signin_url, array('signin' => array(
      'username' => 'johndoe',
      'password' => '123456')))->
    followRedirect()->
    with('user')->isAuthenticated()->
  info('  Add Test Venue')->
  post('venue/add', array('venue' => $venue))->
    with('user')->isAuthenticated()->
    with('response')->isStatusCode(200)->
  info('  Verify if Test Venue is added')->
  get('venue')->
    with('response')->begin()->
      checkElement('h3.item_name:contains("Test Venue")', true)->
    end()->
  info('  Adding Test Deal')->
  post('/deal/add', array('deal' => $deal))->
    with('response')->isStatusCode(200)->
  info('  Checking if Test Deal is added')->
  get('deal?deal_state=hidden')->
    with('response')->begin()->
      isStatusCode(200)->
      isHeader('content-type', 'text/html; charset=utf-8')->
      checkElement('h3.item-name:contains("Test Deal")', true)->
      debug()->
    end()
;

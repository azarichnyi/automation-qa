<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

Doctrine_Core ::loadData(sfConfig::get('sf_test_dir').'/fixtures/user0');
$browser = new sfTestFunctional(new sfBrowser());
$commute = array(
    'id' => 0,
    'start_point' => array('lat' => 50.46016, 'lon' => 30.463),
    'finish_point' => array('lat' => 50.4531, 'lon' => 30.4891),
    'via_points' => array(array('lat' => 50.446116, 'lon' => 30.4694)),
    'name' => 'test commute name',
    'sw_point' =>
      array('description' => 'SW point', 'lat' =>50.443, 'lon' => 50.443),
    'ne_point' =>
       array('description' => 'NE point', 'lat' =>50.46357, 'lon' => 50.46357),
    'polyline' => 'oo~rHww|xDeDwDc@QqGmAmAzWItGZdp@?bD[jEP|PU~A_@H[GUa@Kw@CoCnUe@rDE~@FhJ`BfTzFrHzBjBx@pg@tNxAHjBE|Bq@`AtFBb@[n@]BYO}@kGyOa`AKqA?}AjD_o@DyHOiDaAuK_G((g@s@eISaEAgEJoENoAnAqHtBoPj@e@wOmE_NiEAsAcEy]}Bz@',
    'steps' => array(
      array('lat' => 50.46024, 'lon' => 30.46284),
      array('lat' => 50.46262, 'lon' => 30.46424),
      array('lat' => 50.46262, 'lon' => 30.46424),
      array('lat' => 50.46292, 'lon' => 30.45018),
      array('lat' => 50.46292, 'lon' => 30.45018),
      array('lat' => 50.46297, 'lon' => 30.44629),
      array('lat' => 50.46297, 'lon' => 30.44629),
      array('lat' => 50.46357, 'lon' => 30.44697),
      array('lat' => 50.46357, 'lon' => 30.44697),
      array('lat' => 50.46276, 'lon' => 30.44701),
      array('lat' => 50.46276, 'lon' => 30.44701),
      array('lat' => 50.45454, 'lon' => 30.44577),
      array('lat' => 50.45454, 'lon' => 30.44577),
      array('lat' => 50.44335, 'lon' => 30.44221),
      array('lat' => 50.44335, 'lon' => 30.44221),
      array('lat' => 50.44342, 'lon' => 30.44062),
      array('lat' => 50.44342, 'lon' => 30.44062),
      array('lat' => 50.44672, 'lon' => 30.46907)
    ));

$browser->info('Test perform various commute actions')->
  info('  Logging In...')->
  post('/user/login', array( 'auth_info' =>
                             '{"email":"johndoe","password":"123456"}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  with('user')->isAuthenticated()->
  
  info('  Creating a commute...')->
  post('/commute/add', array('commute' => json_encode($commute)))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
    matches('/id\":\d+/')->
  end();

$response = json_decode($browser->getResponse()->getContent(), true);
$cid = $response['commute']['id'];

$browser-> 
  info('  Checking commutes list')->
  get('/commute')->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
    matches('/suggestions\":\[\]/')->
  end()->
  
  info('  Getting deals around commute #$cid (should be false)')->
  get('/commute/deals?id='.$cid)->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
    matches('/deals\":\[\]/')->
end();

$commute['id'] = $cid;
$commute['name'] = 'modified name for test commute';

$browser->
  info('  Updating the commute')->
  post('/commute/update', array('commute' => json_encode($commute)))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Checking commutes list again')->
  get('/commute')->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
    matches("/id\":\"$cid/")->
    matches('/name\":\"modified name for test commute\"/')->
    matches('/suggestions\":\[\]/')->
  end()->
  
  info('  Deleting Commute')->
  post('/commute/delete/'.$cid, array('id' => $cid))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  
  info('  Checking commutes list once more')->
  get('/commute')->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
    matches('/commute\":\[\]/')->
    matches('/suggestions\":\[\]/')->
  end()
;

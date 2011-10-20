<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

cpMemcache::getInstance()->delete('tiles1');

$browser = new TrackTestFunctional(new sfBrowser());
$browser->loadData();

$browser->
    info('1 - Pb: Dynamic Driving')->
    
    info('1.1 - New point in the tile')->
    setCookie('token', '1', time()+60*60*24*365*20, '/')->
    
    post('/track/add', array('points' => '{"locations":[{"lat":50.4113,"lon":30.5283,"speed":10,"timestamp":"2011-04-20 18:07:31 +0000"}]}'))->
    
    with('response')->begin()->
        isStatusCode(200)->
        isHeader('content-type', 'application/json; charset=utf-8')->
        matches('/"pb_dd":0\.25,/')->
        matches('/"pb_dc":0\.1,/')->
        matches('/"pb_cc":0}/')->
    end()
;

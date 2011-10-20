<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$auth_data = array(
    'email'                 => "rodush@ua.fm",
    'name'                  => "Roman",
    'password'              => "rodush",
    'password_confirmation' => "rodush"
);

$guardUser = sfGuardUserTable::getInstance()->findOneBy('email_address', $auth_data['email']);

$browser = new sfTestFunctional(new sfBrowser());
$browser->
    info('Registration')->
    post('/user/signup', array('auth_info' => json_encode($auth_data)));

// Check response
$browser->with('response')->begin()
            ->isStatusCode(200)
            ->isHeader('content-type', 'application/json; charset=utf-8')
            ->matches('/status":{"success":\s*' . (is_object($guardUser) ? 'false' : 'true') . '/');

// Leave now
$browser->end();

unset($auth_data['password_confirmation'], $auth_data['name']);
$browser->info('Login')
            ->post('/user/login', array('auth_info' => json_encode($auth_data)));

// Check request
//$browser->with('request')->isForwardedTo('home', 'index');
            
// Check response
$browser->with('response')->begin()
            ->isStatusCode(200)
            ->isHeader('content-type', 'application/json; charset=utf-8')
//            ->setsCookie('token')
            ->end();

// check user -  must be authenticated
$browser->with('user')->begin()
            ->isAuthenticated(true)
            ->end();

// check user - must be logget out
$browser->info('LogOut')
            ->get('user/logout')
            ->with('user')
            ->begin()
            ->isAuthenticated(false)
            ->end();

//$browser->with('response')->begin()->debug()->end();
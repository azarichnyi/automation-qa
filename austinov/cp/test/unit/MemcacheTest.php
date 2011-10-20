<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';


cpMemcache::getInstance()->delete('tiles1');


$t = new lime_test(1);
$t->comment('Test empty memcache');
$temp = cpMemcache::getInstance()->get('tiles1');
$t->is($temp, false);


$t = new lime_test(2);
$t->comment('Adding new value to memcache');
$res =cpMemcache::getInstance()->set('tiles1', 'test');
$t->is($res, true);
$res =cpMemcache::getInstance()->get('tiles1');
$t->is($res, 'test');

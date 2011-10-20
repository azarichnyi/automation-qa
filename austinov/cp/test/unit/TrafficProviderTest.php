<?php

// We want to test "api" application-specific features
define('APP', 'api');

include(dirname(__FILE__).'/../bootstrap/Doctrine.php');
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/unit');

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/rodush0');
$commutes = CommuteTable::getInstance()->findAll()->toArray();
$commute = reset($commutes);

$t_p = new Traffic(new Traffic_Provider_Inrix());
$cachedDelay = $t_p->getCachedRouteDelay($commute['id']);

$t = new lime_test(2);
$t->comment('Testing "Inrix" traffic provider');

// Create instance of Traffic provider using Inrix service
$inrix = new Traffic_Provider_Inrix;
$t->ok($inrix->isInitialized(), '"Inrix" intialized successfully');
$t->is($cachedDelay, 0, "Empty cached delay for new commute");

// @todo: add test after commute editing
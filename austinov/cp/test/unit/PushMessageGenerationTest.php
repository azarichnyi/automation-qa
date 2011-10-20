<?php

include(dirname(__FILE__) . '/../bootstrap/unit.php');

$t = new lime_test(4);

$delays = array('bad', 'good', 'bad');
$t->is(prepareCommutesPushMesage($delays), '2 of your commutes are bad, 1 is good', '2 bad, 1 good');

$delays = array('bad', 'good', 'good');
$t->is(prepareCommutesPushMesage($delays), '1 of your commute is bad, 2 are good', '1 bad, 2 good');

$delays = array('good');
$t->is(prepareCommutesPushMesage($delays), 'Your commute is good', '1 is good');

$delays = array('bad', 'good', 'undefined');
$t->is(
    prepareCommutesPushMesage($delays),
    '1 of your commute is bad, 1 is good, 1 is undefined', '1 good, 1 bad, 1 undefined'
);
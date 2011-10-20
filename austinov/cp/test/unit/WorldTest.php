<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';



// cloudmade_getCity -------------------------------------------------------------------

$t = new lime_test(1);
$t->comment('cloudmade_getCity - (point outside city)');
$city = World::cloudmade_getCity(50.52098, 31.093941);
$t->is($city->getOsmId(), null);

$t = new lime_test(1);
$t->comment('cloudmade_getCity - (point in city Kiev)');
$city = World::cloudmade_getCity(50.437019, 30.544338);
$t->is($city->getOsmId(), 26150422);


$t = new lime_test(2);
$t->comment('cloudmade_getCity - (point in city Hrevaha)');
$city = World::cloudmade_getCity(50.264672, 30.32474);
$t->is($city->getOsmId(), 337525824);

$t->comment('spAddCity - (adding new city)');
$res = World::spAddCity($city);
$planet_city = Doctrine_Core::getTable('PlanetCity')->findOneByOsmId(337525824);
$t->is($planet_city instanceof PlanetCity, true);











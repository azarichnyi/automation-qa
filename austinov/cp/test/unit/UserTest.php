<?php

define('APP', 'api');

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';
require_once dirname(__FILE__).'/model/UserTest.class.php';
require_once dirname(__FILE__).'/model/PointTest.class.php';
require_once dirname(__FILE__).'/model/PainBucksTest.class.php';

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/unit');

$painBucks = new PainBucksTest();
$painBucks->dynamicDriving = 1;
$painBucks->dynamicCommunity = 2;
$painBucks->communityCommunity = 3;
$painBucks->contrib = 4;

$point = new PointTest();
$point->painBucks = $painBucks;
$point->received = '2011-08-04 00:01:02 +0000';

// Create testing object
$t = new lime_test(31);

// addPainBucks -------------------------------------------------------------
$t->comment('addPainBucks');
$user = new UserTest();
$user->addPainBucks($point);
$details = $user->getPainBucksDetails();
$t->is($user->getPainbucksDd(), 1, 'Correct "DD" painbuckses amount');
$t->is($user->getPainbucksDc(), 2, 'Correct "DC" painbuckses amount');
$t->is($user->getPainbucksCc(), 3, 'Correct "CC" painbuckses amount');
$t->is($user->getPainbucksContrib(), 4, 'Correct "Contribution" painbuckses amount');
$t->is(count($details), 1, 'One element in "PainbuckDetails" array for the user');
$t->is($details[0]['received'], '2011-08-04 00:01:02', 'Correct time of painbuckses receiving');
$t->is($details[0]['dd'], 1, 'Correct "DD" painbuckses amount returned in details');
$t->is($details[0]['dc'], 2, 'Correct "DC" painbuckses amount returned in details');
$t->is($details[0]['cc'], 3, 'Correct "CC" painbuckses amount returned in details');
$t->is($details[0]['contrib'], 4, 'Correct "Contrib" painbuckses amount returned in details');


// save ---------------------------------------------------------------------
$t->comment('Save');
$user = Doctrine_Core::getTable('User')->findOneBySfGuardUserId(1);
$user->addPainBucks($point);
$user->save();

$user2 = Doctrine_Core::getTable('User')->findOneBySfGuardUserId(1);
$t->is($user2->getPainbucksDd(), 1);
$t->is($user2->getPainbucksDc(), 2);
$t->is($user2->getPainbucksCc(), 3);
$t->is($user2->getPainbucksContrib(), 4);

$painBucksHistory = Doctrine_Core::getTable('PainBucksHistory')->findOneByUserId(1);
$t->is($painBucksHistory->getPainbucksDd(), 1);
$t->is($painBucksHistory->getPainbucksDc(), 2);
$t->is($painBucksHistory->getPainbucksCc(), 3);
$t->is($painBucksHistory->getPainbucksContrib(), 4);
$t->is($painBucksHistory->getReceived(), '2011-08-04 00:01:02');


// addPainBucksDd
$t->comment('addPainBucksDd');

$user = new UserTest();
$user->setPainbucksAvgCoef(2);
$user->addPainBucksDd(10);
$t->is($user->getPainbucksDd(), 20);
$t->is($user->getPainbucks(), 20);
$t->is($user->getCashAmount(), 20);

// addPainBucksDc
$t->comment('addPainBucksDc');

$user = new UserTest();
$user->setPainbucksAvgCoef(2);
$user->addPainBucksDc(10);
$t->is($user->getPainbucksDc(), 20);
$t->is($user->getPainbucks(), 20);
$t->is($user->getCashAmount(), 20);

// addPainBucksCc
$t->comment('addPainBucksCc');

$user = new UserTest();
$user->setPainbucksAvgCoef(2);
$user->addPainBucksCc(10);
$t->is($user->getPainbucksCc(), 20);
$t->is($user->getPainbucks(), 20);
$t->is($user->getCashAmount(), 20);


// addPainBucksContrib
$t->comment('addPainBucksContrib');

$user = new UserTest();
$user->setPainbucksAvgCoef(2);
$user->addPainBucksContrib(10);
$t->is($user->getPainbucksContrib(), 10);
$t->is($user->getPainbucks(), 10);
$t->is($user->getCashAmount(), 10);

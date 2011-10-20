<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

$t = new lime_test(5);
$t->comment('checkDeal: Everything is OK');
$deal = new Deal();
$deal->setTotalCount(100);
$deal->setBookedCount(50);
$deal->setState(Deal::STATE_STOCK);
try {
    $deal->checkDeal();
    $t->is('all ok', 'all ok');
} catch (cpException $ex) {
    $t->is('not all ok', 'all ok');
}

$t->comment('checkDeal: No coupons');
$deal = new Deal();
$deal->setTotalCount(100);
$deal->setBookedCount(100);
$deal->setState(Deal::STATE_STOCK);
try {
    $deal->checkDeal();
    $t->is('all ok', 'not all ok');
} catch (cpException $ex) {
    $t->is('not all ok', 'not all ok');
}

$t->comment('checkDeal: Status not in stock');
$deal = new Deal();
$deal->setTotalCount(101);
$deal->setBookedCount(100);
$deal->setState(Deal::STATE_HIDDEN);
try {
    $deal->checkDeal();
    $t->is('all ok', 'not all ok');
} catch (cpException $ex) {
    $t->is('not all ok', 'not all ok');
}

$t->comment('Testing image url generation');

require_once 'model/DealTest.class.php';

$mock = new DealTest;
$mock2 = new DealTestNoImage;

$t->is($mock->getImageUrl(), 'http:///uploads/deals/thumb_image.jpg', 'Not empty deal image returned');
$t->is(
    $mock2->getImageUrl(),
    'http:///img/deal/category/cp_img_web_bar.png',
    'Empty deal image replaced by category image'
);
<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/coupon/coupon.yml');

$deal = new Deal();
$deal->setPrice(30);
$deal->setPriceCc(20);


// generateCode
$t = new lime_test(1);
$t->comment('generateCode: Code check');
$coupon = new Coupon();
$coupon->generateCode();
$t->is(strlen($coupon->getCode()), 10);

// Saving Coupon
/* @var $deal Deal */
$user = UserTable::getInstance()->find(1);
$deal = DealTable::getInstance()->find(1);

$coupon = new Coupon();
$coupon->setId(1);
$coupon->setDeal($deal);
$coupon->setUser($user);
$coupon->setStatus(Coupon::STATUS_UNPAID);
$coupon->setVisibility(true);
$coupon->setPurchaseType('paypal');
$coupon->setPurchasePrice(10);
$coupon->setPurchasePriceCc(50);
$coupon->save();

$t = new lime_test(7);
$t->comment('Saving coupon');
/* @var $coupon_check Coupon */
$coupon_check = CouponTable::getInstance()->find(1);
$t->is($coupon_check->getId(), 1);
$t->is($coupon_check->getDealId(), 1);
$t->is($coupon_check->getUserId(), 1);
$t->is($coupon_check->getStatus(), Coupon::STATUS_UNPAID);
$t->is($coupon_check->getPurchaseType(), 'paypal');
$t->is($coupon_check->getPurchasePrice(), 10);
$t->is($coupon_check->getPurchasePriceCc(), 50);




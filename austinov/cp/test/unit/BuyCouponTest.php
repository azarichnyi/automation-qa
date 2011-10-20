<?php

/*
 * Testing correct behavior of purchasing a coupon for deal
 */

define('APP', 'api');

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

// Load data fixtures
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/unit');

// checkFundsForPurchasingCoupon =====================================
$deal = new Deal();
$deal->setPriceCc(50);
$deal->setPrice(30);

$deal2 = new Deal();
$deal2->setPriceCc(0);
$deal2->setPrice(30);

$user = new User();
$user->setCashAmount(40);

$richUser = new User();
$richUser->setCashAmount(50);

// Create testing object
$t = new lime_test(24);

$t->comment('checkFundsForPurchasingCoupon: not enough money');
try 
{
    $user->checkFundsForPurchasingCoupon($deal);
    $t->fail('User does not have enough commute cash to buy coupon');
} 
catch (cpException $ex) 
{
    $t->is('not all ok', 'not all ok', 'User does not have enough commute cash to buy coupon');
}

$t->comment('checkFundsForPurchasingCoupon: enough money');
try 
{
    $richUser->checkFundsForPurchasingCoupon($deal);
    $t->pass('User have enough commute cash to buy the deal');
} 
catch (cpException $ex) 
{
    $t->fail('User have enough commute cash to buy the deal');
}

// purchaseCouponByMoney =====================================================================
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/paypal_invoice/purchase_process.yml');

$t->comment('purchaseCoupon: Success purchasing');

$billingInfo = array(
    'creditcardtype' => 'Visa',
    'acct' => '4334177404902749',
    'cvv2' => '324',
    'expdate' => '092016',
    'firstname' => 'Alexander',
    'lastname' => 'Bestcenny',
    'street' => '19540 Jamboree Road',
    'city' => 'Irvine',
    'state' => 'CA',
    'countrycode' => 'US',
    'zip' => '92612',
);

$user = UserTable::getInstance()->find(1);
$deal = DealTable::getInstance()->find(2);
$purchasedCoupon = $user->purchaseCoupon($deal, $billingInfo, '195.20.130.1');

/* @var $userCheck User */
$userCheck = UserTable::getInstance()->find(1);
/* @var $dealCheck Deal */
$dealCheck = DealTable::getInstance()->find(2);
/* @var $couponCheck Coupon */
$couponCheck = CouponTable::getInstance()->findOneByUserId(1);

$t->is($userCheck->getCashAmount(), 50, 'User has correct cash amount');
$t->is($dealCheck->getBookedCount(), 50, 'After user buys coupon deal booked counter increments');
$t->is($dealCheck->getState(), Deal::STATE_SOLD, 'If sold last coupon deal gets into "sold" state');
$t->is(strlen($couponCheck->getCode()), 10, 'Length of coupon code is correct');
$t->is($couponCheck->getPurchaseType(), Coupon::PURCHACE_TYPE_CC, 'Purchase type is "CommuteCash" only');
$t->is($couponCheck->getStatus(), Coupon::STATUS_UNLOCKED, 'Coupon gets unlocked when successfully bought');
$t->is($couponCheck->getPurchaseId(), null, 'There is no relation between coupon and paypal invoice');


Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/paypal_invoice/purchase_process.yml');

$t->comment('purchaseCoupon: Failed purchasing');

$billingInfo = array(
    'creditcardtype' => 'Visa',
    'acct' => 'incorrect',
    'cvv2' => '324',
    'expdate' => '092016',
    'firstname' => 'Alexander',
    'lastname' => 'Bestcenny',
    'street' => '19540 Jamboree Road',
    'city' => 'Irvine',
    'state' => 'CA',
    'countrycode' => 'US',
    'zip' => '92612',
);

$user = UserTable::getInstance()->find(2);
$deal = DealTable::getInstance()->find(1);

$coupon = null;
try
{
    $coupon = $user->purchaseCoupon($deal, $billingInfo, '195.20.130.1');
    $t->fail("Incorrect billing data fails purchacing");
}
catch (Exception $e)
{
    $t->pass("Incorrect billing data fails purchacing" . $e->getMessage());
}

/* @var $userCheck User */
$userCheck = UserTable::getInstance()->find(2);
/* @var $dealCheck Deal */
$dealCheck = DealTable::getInstance()->find(1);
/* @var $couponCheck Coupon */
$couponCheck = CouponTable::getInstance()->findOneByUserId(2);

$t->is($coupon, null, "If PayPal transaction fails we get no coupons");
$t->is($userCheck->getCashAmount(), 100);
$t->is($dealCheck->getBookedCount(), 49);
$t->is($dealCheck->getState(), Deal::STATE_STOCK);
$t->is($couponCheck->getPurchaseType(), Coupon::PURCHACE_TYPE_PAYPAL_CC, '"RealPrice+CommuteCash"');
$t->is($couponCheck->getCode(), '');
$t->is($couponCheck->getStatus(), Coupon::STATUS_UNPAID);
$t->isnt($couponCheck->getPurchaseId(), null, 'There is a relation betwee coupon and paypal transaction');


// purchaseCouponByCC -------------------------------------------------------------------------
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/paypal_invoice/purchase_process.yml');

$coupon = CouponTable::getInstance()->findOneByUserId(3);
$user = UserTable::getInstance()->find(3);


$t->comment('purchaseCouponByCC: Success Buying');

$dealCC = DealTable::getInstance()->find(2);
$user->purchaseCoupon($dealCC);

/* @var $userCheck User */
$userCheck = UserTable::getInstance()->find(3);
/* @var $dealCheck Deal */
$dealCheck = DealTable::getInstance()->find(2);
/* @var $couponCheck Coupon */
$couponCheck = CouponTable::getInstance()->findOneByUserId(3);

$t->is($userCheck->getCashAmount(), 50);
$t->is($dealCheck->getBookedCount(), 50);
$t->is($dealCheck->getState(), Deal::STATE_SOLD);
$t->is(strlen($couponCheck->getCode()), 10);
$t->is($couponCheck->getStatus(), Coupon::STATUS_UNLOCKED);
$t->is($couponCheck->getPurchaseType(), Coupon::PURCHACE_TYPE_CC);

<?php

define('APP', 'api');

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/paypal_invoice/paypal_invoice.yml');

// isSuccess ====================================================
$t = new lime_test(1);
$t->comment('isSuccess: Success');
$ppi = new Paypalinvoice();
$ppi->setAck(Paypalinvoice::PAYMENT_STATUS_SUCCESS);
$t->is($ppi->isSuccess(), true);

$t = new lime_test(1);
$t->comment('isSuccess: Success');
$ppi = new Paypalinvoice();
$ppi->setAck(Paypalinvoice::PAYMENT_STATUS_SUCCESS_WITH_WARNING);
$t->is($ppi->isSuccess(), true);

$t = new lime_test(1);
$t->comment('isSuccess: Success');
$ppi = new Paypalinvoice();
$ppi->setAck(Paypalinvoice::PAYMENT_STATUS_SUCCESS_FAILURE);
$t->is($ppi->isSuccess(), false);

// process ======================================================
/* @var $coupon Coupon */
$coupon = CouponTable::getInstance()->findOneByUserId(1);

$t = new lime_test(18);
$t->comment('process: successful payment through paypal');
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
$paypalInvoice = new Paypalinvoice();
$paypalInvoice->setId(1);
$paypalInvoice->mapBillingInfo($billingInfo);
$paypalInvoice->setAtm($coupon->getPurchasePrice());
$paypalInvoice->setCurrency('USD');
$paypalInvoice->setUserId($coupon->getUserId());
$paypalInvoice->setIp('195.20.130.1');
$paypalInvoice->setInvnum('coupon_'.$coupon->getId());
$paypalInvoice->setDescript('Coupon buying: ' . $coupon->getId());
$paypalInvoice->process();

/* @var $checkPPI PaypalInvoice */
$checkPPI = PaypalInvoiceTable::getInstance()->find(1);
$t->is($checkPPI->getUserId(), 1);
$t->is($checkPPI->getAcct(), '2749');
$t->is($checkPPI->getAck(), 'Success');
$t->is($checkPPI->getAtm(), 40);
$t->is($checkPPI->getCity(), 'Irvine');
$t->is($checkPPI->getCountrycode(), 'US');
$t->is($checkPPI->getCreditcardtype(), 'Visa');
$t->is($checkPPI->getCurrency(), 'USD');
$t->is($checkPPI->getInvnum(), 'coupon_'.$coupon->getId());
$t->is($checkPPI->getDescript(), 'Coupon buying: '.$coupon->getId());
$t->is($checkPPI->getErrorCode(), '');
$t->is($checkPPI->getErrorLdesc(), '');
$t->is($checkPPI->getErrorSdesc(), '');
$t->is($checkPPI->getFirstName(), 'Alexander');
$t->is($checkPPI->getLastName(), 'Bestcenny');
$t->is($checkPPI->getState(), 'CA');
$t->is($checkPPI->getStreet(), '19540 Jamboree Road');
$t->is($checkPPI->getZip(), '92612');

$t = new lime_test(16);
$t->comment('process: failed payment through paypal - incorrect cc number');
$coupon = CouponTable::getInstance()->findOneByUserId(2);

$billingInfo = array(
    'creditcardtype' => 'Visa',
    'acct' => 'failed',
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
$paypalInvoice = new Paypalinvoice();
$paypalInvoice->setId(2);
$paypalInvoice->mapBillingInfo($billingInfo);
$paypalInvoice->setAtm($coupon->getPurchasePrice());
$paypalInvoice->setCurrency('USD');
$paypalInvoice->setUserId($coupon->getUserId());
$paypalInvoice->setIp('195.20.130.1');
$paypalInvoice->setInvnum('coupon_'.$coupon->getId());
$paypalInvoice->setDescript('Coupon buying: ' . $coupon->getId());
$paypalInvoice->process();

/* @var $checkPPI PaypalInvoice */
$checkPPI = PaypalInvoiceTable::getInstance()->find(2);
$t->is($checkPPI->getUserId(), 2);
$t->is($checkPPI->getAck(), 'Failure');
$t->is($checkPPI->getAtm(), 50);
$t->is($checkPPI->getCity(), 'Irvine');
$t->is($checkPPI->getCountrycode(), 'US');
$t->is($checkPPI->getCreditcardtype(), 'Visa');
$t->is($checkPPI->getCurrency(), 'USD');
$t->is($checkPPI->getInvnum(), 'coupon_'.$coupon->getId());
$t->is($checkPPI->getDescript(), 'Coupon buying: '.$coupon->getId());
$t->is($checkPPI->getErrorCode(), '10527');
$t->is($checkPPI->getErrorSdesc(), 'Invalid Data');
$t->is($checkPPI->getFirstName(), 'Alexander');
$t->is($checkPPI->getLastName(), 'Bestcenny');
$t->is($checkPPI->getState(), 'CA');
$t->is($checkPPI->getStreet(), '19540 Jamboree Road');
$t->is($checkPPI->getZip(), '92612');

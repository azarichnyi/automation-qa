<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/mappers/coupon_mapper.yml');

// Mapper_DealBuy ------------------------------------------------------------------
$t = new lime_test(1);
$t->comment('Mapper_DealBuy checkInputCompleteness: Correct');
try 
{
    Mapper_DealBuy::checkInputCompleteness(array(
    		'id' => 1, 
    		'billing_info' => array(
                'creditcardtype' => '1',
                'acct' => '2',
                'expdate' => '3',
                'cvv2' => '4',
                'firstname' => '5',
                'lastname' => '6',
                'street' => '7',
                'city' => '8',
                'state' => '9',
                'countrycode' => '10',
                'zip' => '11',
            )
    ));    
    $t->is(1, 1);
} 
catch (cpException $e) 
{
    $t->is(1, 2);        
}

$t = new lime_test(1);
$t->comment('Mapper_DealBuy checkInputCompleteness: No id');
try 
{
    Mapper_DealBuy::checkInputCompleteness(array(
    		'billing_info' => array(
                'creditcardtype' => '1',
                'acct' => '2',
                'expdate' => '3',
                'cvv2' => '4',
                'firstname' => '5',
                'lastname' => '6',
                'street' => '7',
                'city' => '8',
                'state' => '9',
                'countrycode' => '10',
                'zip' => '11',
            )
    ));    
    $t->is(2, 1);
} 
catch (cpException $e) 
{
    $t->is(1, 1);        
}


// Billing Info ==================================================================================
$t = new lime_test(1);
$t->comment('Mapper_BillingInfo checkInputCompleteness: Correct billing_info');
$t->is(Mapper_BillingInfo::checkInputCompleteness(array(
                'creditcardtype' => '1',
                'acct' => '2',
                'expdate' => '3',
				'cvv2' => '4',
                'firstname' => '5',
                'lastname' => '6',
                'street' => '7',
                'city' => '8',
                'state' => '9',
                'countrycode' => '10',
                'zip' => '11',
    )), true);    

$t = new lime_test(1);
$t->comment('Mapper_BillingInfo checkInputCompleteness: Incorrect billing_info');
$t->is(Mapper_BillingInfo::checkInputCompleteness(array(
                'creditcardtype' => '1',
                'acct' => '2',
                'expdate' => '3',
                'firstname' => '5',
                'city' => '8',
                'state' => '9',
                'countrycode' => '10',
                'zip' => '11',
    )), false);    

    
// Mapper_Coupon =============================================================================

$coupon = CouponTable::getInstance()->find(1);    

$t = new lime_test(4);
$t->comment('Mapper_Coupon mapGetFields');

$coupon_m = Mapper_Coupon::mapGetFields($coupon);
$t->is($coupon_m['id'], 1);
$t->is($coupon_m['code'], '1234567890');
$t->is($coupon_m['status'], Coupon::STATUS_UNLOCKED);
$t->is(is_array($coupon_m['deal']), true);



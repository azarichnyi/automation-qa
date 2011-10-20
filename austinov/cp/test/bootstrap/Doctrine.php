<?php

include(dirname(__FILE__).'/unit.php');
 
if (!defined('APP'))
{
    define('APP', 'frontend');
}
//$env = Environment::getInstance()->getCurrentEnv();
//
//$logger = new sfFileLogger(new sfEventDispatcher(), 
//						   array('file' => sfConfig::get('sf_log_dir') . '/task.log'));
//$logger->log($env);
$env = 'test';


$configuration = ProjectConfiguration::getApplicationConfiguration(APP, $env, true);

new sfDatabaseManager($configuration);


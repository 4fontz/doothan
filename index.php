<?php
require_once 'protected/vendor/autoload.php';
// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';

if(file_exists(dirname(__FILE__).'/protected/config/production.php')){
	$config=dirname(__FILE__).'/protected/config/production.php';
} else if(file_exists(dirname(__FILE__).'/protected/config/main_dev.php')){
	$config=dirname(__FILE__).'/protected/config/main_dev.php';
} else{
	$config = dirname(__FILE__).'/protected/config/main.php';
}

/// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_STRICT | E_DEPRECATED);
error_reporting(E_ALL ^ E_NOTICE);

require_once($yii);
Yii::createWebApplication($config)->run();


//Utility functions
/*function pre($value)
{
	echo '<pre>';
	print_r($value);
	echo '</pre>';
}

function stop($value) {
	pre($value);
	exit;
}*/

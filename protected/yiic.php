<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

defined('YII_DEBUG') or define('YII_DEBUG',true);
require_once($yiic);
Yii::createConsoleApplication($config)->run();

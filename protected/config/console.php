<?php
// protected/config/console.php
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Console Application',
    
    'import'=>array(
        'application.models.*',
    ),
    
    // application components
    'components'=>array(
        'db'=>array(
            'class'=>'CDbConnection',
            'charset' => 'utf8',
            'connectionString'=>'mysql:host=localhost;dbname=doothanprod',
            'username'=>'root',
            'password'=>''
        ),
        // usefull for generating links in email etc...
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName' => FALSE,
            'rules'=>array(),
        ),
    ),
);
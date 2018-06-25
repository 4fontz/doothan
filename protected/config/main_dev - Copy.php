<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');
Yii::setPathOfAlias('vendor', dirname(__FILE__) . '/../vendor');
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Dhoothan',
    'defaultController' => 'administrator', 
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.yiisortablemodel.*',
        'ext.YiiMailer.YiiMailer',
        'ext.morris.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii',
            ),
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('*', '::1'),
        ),
    ),
    // application components
    // application components
    'components' => array(
        'clientScript' => array(
            'scriptMap' => array(
                // 'jquery.js' => false,
                // 'jquery.ba-bbq.min.js' => false,
               // 'jquery.ba-bbq.js'=>false,
                // 'core.css'      => false,
                // 'styles.css'    => false,
                // 'pager.css'     => false,
                // 'default.css'   => false,
            ),
           
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => array('administrator')
        ),
        'phpThumb' => array(
            'class' => 'ext.EPhpThumb.EPhpThumb',
            'options' => array()
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                'gii' => 'gii',
                'gii/<controller:\w+>' => 'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
            'showScriptName' => false
        ),
        'db' => array(
            //'connectionString' => 'mysql:host=cloud9.crskpqcg2cxx.us-west-2.rds.amazonaws.com;dbname=cloud9_dev',
            'connectionString' => 'mysql:host=localhost;dbname=doothanprod',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'doothan',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, info',
                ),
                // uncomment the following to show log messages on web pages
                
                // array(
                // 'class'=>'CWebLogRoute',
                // ),
                
            ),
        ),
        'Smtpmail' => array(
            'class' => 'application.extensions.smtpmail.PHPMailer',
            'Host' => "smtp.gmail.com",
            'Username' => 'achusoman12@gmail.com',
            'Password' => 'achusoman@8357',
            'Mailer' => 'smtp',
            'Port' => 587,
            'SMTPAuth' => true,
        )
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'info@doothan.in',
        'emailNoReply' =>'info@doothan.in',
        'supportEmail' => 'info@doothan.in',
        'facebookAppId' => '146898185951447',
        'facebookAppSecret' => '74354f349efa3da19704ecc97cb0f685',
        'maxFtIsoNum' => '1000',
        'maxCellarNum' => '1000',
        'paginationLimit' => 15,
        'miniPaginationLimit' => 5,
        'userPaginationLimit' => 50,
        'awsKey' => 'AKIAIWPVPGHJWFDJ6P3Q',
        'awsSecret' => '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',
        'awsRegion' => 'ap-south-1',
        'profileImageBucket' => 'doothan-mumbai-dev/doothan-user-profile',
        'profileImageBucketUrl' => 'https://ap-south-1.amazonaws.com/doothan-mumbai-dev/doothan-user-profile/',
        'adharImageBucket' => 'doothan-mumbai-dev/Adhar',
        'adharImageBucketUrl' => 'https://ap-south-1.amazonaws.com/doothan-mumbai-dev/Adhar/',
        'photoImageBucket' => 'doothan-mumbai-dev/Photos',
        'photoImageBucketUrl' => 'https://ap-south-1.amazonaws.com/doothan-mumbai-dev/Photos/',
        'requestImageBucket' => 'doothan-mumbai-dev/Request',
        'requestImageBucketUrl' => 'https://ap-south-1.amazonaws.com/doothan-mumbai-dev/Request/',
        'uploadPath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../uploads/',
        'accessTokenExpiry' => 3600 * 24 * 365,
        'massage_duration' => array('60', '90', '120'),
        'gender' => array('male', 'female', 'either'),
        //'fromMail' => 'reshma.xtapps@gmail.com',
        'fromName' => 'doothan',
        //'toMail' => 'reshma.xtapps@gmail.com',
        'google_api_key'=>'AIzaSyCs__enBfa-NqHY-odNZ-ICz4U7l0uKfGI',
        'toName' => 'doothan',
        'max_distance'=>5,
        'notification_time'=>10*60,
        /*'nexmoKey'=>'5013f5ff',
        'nexmoSecret'=>'bf7f7a7f',
        'nexmoSenderId'=>13866283343,*/
        
         'nexmoKey'=>'81c5f9cf',
         'nexmoSecret'=>'1a7e06a82db68cb7',
         'nexmoSenderId'=>12017541446, 
        
        '_SALT'=>'RTjF7emWyA',
        'minimum_km'=>100,
        'default_weight_limit'=>10,
        'default_distance_limit'=>30,
        'default_weight_limit_charge'=>50,
        'default_weight_charge'=>50,
        'default_distance_limit_charge'=>1.25,
        'default_distance_charge'=>1.25
    ),
);


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
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => array('site/login')
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
            'connectionString' => 'mysql:host=doodhan.c0n3rndkucce.us-east-2.rds.amazonaws.com;dbname=doodhan_dev',
            'emulatePrepare' => true,
            'username' => 'doodhan',
            'password' => 'q0isrKUkoruGlT9s',
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
            'Username' => 'web.xtapps@gmail.com',
            'Password' => 'xtapps123',
            'Mailer' => 'smtp',
            'Port' => 587,
            'SMTPAuth' => true,
        )
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'no-replay@doothan.com',
        'emailNoReply' =>'no-replay@doothan.com',
        'supportEmail' => 'no-replay@doothan.com',
        'facebookAppId' => '1805078886427360',
        'facebookAppSecret' => '2ca8d33c30841fa08ea377c372dc56c7',
        'maxFtIsoNum' => '1000',
        'maxCellarNum' => '1000',
        'paginationLimit' => 15,
        'miniPaginationLimit' => 5,
        'userPaginationLimit' => 50,
        'awsKey' => 'AKIAJFCOCQVI7IAGJE2Q',
        'awsSecret' => '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',
        'awsRegion' => 'us-west-2',
        'profileImageBucket' => 'doothan-user-profile',
        'profileImageBucketUrl' => 'https://s3-us-west-2.amazonaws.com/doothan-user-profile/',
        'uploadPath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../uploads/',
        'accessTokenExpiry' => 3600 * 24 * 365,
        'massage_duration' => array('60', '90', '120'),
        'gender' => array('male', 'female', 'either'),
        'fromMail' => 'reshma.xtapps@gmail.com',
        'fromName' => 'doothan',
        'toMail' => 'reshma.xtapps@gmail.com',
        'toName' => 'doothan',
        'max_distance'=>5,
        'notification_time'=>10*60,
    ),
);

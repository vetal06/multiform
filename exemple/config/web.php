<?php

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'vetal06\multiform\exemple\controllers',
//    'controllerMap' => [
//        'site' => 'vetal06\multiform\exemple\controllers\SiteController',
//    ],
    'defaultRoute' => 'site/index',
    'components' => [
        'assetManager' => [
            'forceCopy' => true,
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceLanguage' => 'ru',
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '02n2Mx7eqTapmAhdEhE5ATU4Qa5Xlwme',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
//        'errorHandler' => [
//            'errorAction' => 'site/error',
//        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'messageConfig' => [
                'from' => ['bbssvv06@gmail.com' => 'Admin'], // this is needed for sending emails
                'charset' => 'UTF-8',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=firesky',
            'username' => 'postgres',
            'password' => 'password',
            'charset' => 'utf8',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [ // не забывать что порядок рулсов играет очень большую роль
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@vendor/amnah/yii2-user/views' => '@app/views/module-user',
                ],
            ],
        ],

    ],

    'params' => [],
];

//if (YII_ENV_DEV) {
//    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//        // uncomment the following to add your IP if you are not connecting from localhost.
//        //'allowedIPs' => ['127.0.0.1', '::1'],
//    ];
//
//    $config['bootstrap'][] = 'gii';
//    $config['modules']['gii'] = [
//        'class' => 'yii\gii\Module',
//        // uncomment the following to add your IP if you are not connecting from localhost.
//        //'allowedIPs' => ['127.0.0.1', '::1'],
//    ];
//} else {
//    $config['components']['assetManager'] = [
//        'bundles' =>require(__DIR__.'/../assets/MinAsset.php'),
//    ];
//}

return $config;

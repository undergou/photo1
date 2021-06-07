<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Gallery',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'photo' => [
            'class' => 'app\modules\photogallery\Module',
        ],
        'admin' => [
            'class' => 'app\modules\photogallery\modules\admin\Module',
        ],
        'page' => [
            'class' => 'app\modules\photogallery\modules\page\Module',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'qjVSn1qm7JpFyZd0PKLFNkC712JWoehr',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //admin module--------------------------------------------
                '' => '/site/index',
                'photo/admin' => '/admin',
                //--------------------------------------------------------
                'photo/admin/category' => '/admin/category',
                'photo/admin/category/index' => '/admin/category/index',
                'photo/admin/category/create' => '/admin/category/create',
                'photo/admin/category/view' => '/admin/category/view',
                'photo/admin/category/update' => '/admin/category/update',
                'photo/admin/category/delete' => '/admin/category/delete',
                'photo/admin/category/images' => '/admin/category/images',
                //--------------------------------------------------------
                'photo/admin/image' => '/admin/image',
                'photo/admin/image/index' => 'admin/image/index',
                'photo/admin/image/create' => '/admin/image/create',
                'photo/admin/image/view' => '/admin/image/view',
                'photo/admin/image/update' => '/admin/image/update',
                'photo/admin/image/delete' => '/admin/image/delete',
                //--------------------------------------------------------
                //page module---------------------------------------------
                'page/<page:\d+>' => '/page/page/index',
                'page/1' => '/page/page/index',
                'page/category/<slug:[\w\-]+>/<page:\d+>' => '/page/page/category',
                'page/category/<slug:[\w\-]+>/1' => '/page/page/category',
                'page/category/<slug:[\w\-]+>' => '/page/page/category',
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

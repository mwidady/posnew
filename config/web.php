<?php
use kartik\mpdf\Pdf;
$params = require(__DIR__ . '/params.php');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],

    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'weua5JjC3kcnaGJQhiMJUxqLhWftNa_Z',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
      //  'clientScript' => [
          //  'scriptMap' => [
        //        'jquery.js' => false,
             //   'jquery.min.js' => false,
           // ],
     //   ],
        'user' => [
            'identityClass' => 'app\models\User',
               // 'class' => 'app\components\User', // extend User component
            //'enableAutoLogin' => true,
            'enableSession' => true,
            'authTimeout' => 300,
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
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            //'showScriptName' => false,
            'rules' => [
            ],
        ],
        'formatter'  => [
            'class' => 'yii\i18n\Formatter',
            'timeZone'        => 'Africa/Dar_es_Salaam',
        ],

        'pdf' => [
            'class' => Pdf::classname(),
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            ],
        'authManager' => [
        'class' => 'yii\rbac\DbManager',
        ],
    ],
    'modules' => [
        'bootstrap' => ['simplechat'],
        'modules' => [
            'simplechat' => [
                'class' => 'bubasuma\simplechat\Module',
            ],
            // ...
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            //
            'downloadAction' => 'gridview/export/download',
             'i18n' => [
                 'class' => 'yii\i18n\PhpMessageSource',
                 'basePath' => '@kvgrid/messages',
                 'forceTranslation' => true
             ],
        ],
            'api' => [
                'class' => 'app\modules\api\Api',
            ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
   // $config['bootstrap'][] = 'debug';
   // $config['modules']['debug'] = [
    //    'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
   // ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],

    ];
}

return $config;

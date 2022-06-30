<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'rnd-backend',
    'name' => 'R&D System',
    'timeZone' => 'Asia/Jakarta',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gii'=>array(
            'class'=>'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1']
        ),
    ],
    'homeUrl' => '/rnd-system/backend/web',
    'components' => [
    //     'view' => [
    //         'theme' => [
    //             'pathMap' => [
    //                '@app/views' => '@vendor/hail812/yii2-adminlte3/src/views'
    //             ],
    //         ],
    //    ],
        'request' => [
            'csrfParam' => '_csrf-backend-rnd',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'class' => 'backend\components\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-backend-rnd', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend-rnd',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\id+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ],
    ],
    'container' => [
        'definitions' => [
            \yii\widgets\LinkPager::class => \yii\bootstrap4\LinkPager::class,
            'yii\bootstrap4\LinkPager' => [
                'options' => [
                    'class' => 'pagination float-right'
                ],
                'prevPageLabel' => 'Prev',
                'nextPageLabel' => 'Next',
                'firstPageLabel' => 'First',
                'lastPageLabel'  => 'Last',
                'maxButtonCount' => 5
            ]
        ],
    ],
    'params' => $params,
];

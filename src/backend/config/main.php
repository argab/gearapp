<?php
$params = array_merge(
    require __DIR__ . '/../../api/config/params.php',
    require __DIR__ . '/../../api/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'admin',
    'language'            => 'ru',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute'        => 'admin',
    'homeUrl'             => ['admin/index'],
    'bootstrap'           => ['log', 'init'],
    'components'          => [
        'init'         => [
            'class' => \backend\bootstrap\Init::class
        ],
        'user'         => [
            'identityClass'   => \common\entities\user\User::class,
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-admin', 'httpOnly' => true],
            'loginUrl'        => ['/admin/login'],
        ],
        'cache'        => [
            'class' => \yii\caching\FileCache::class,
        ],
        'session'      => [
            'name'  => 'BACKSESSID',
            'class' => \yii\web\Session::class,
            //                'class' => 'yii\redis\Session',
            //                'redis' => [
            //                    'hostname' => 'localhost',
            //                    'port' => xxxx,
            //                    'database' => 0,
            //                ]
            //                'savePath' => __DIR__ . '/../tmp',
        ],
        'assetManager' => [
            'appendTimestamp' => false,
            'linkAssets'      => getenv('LINK_ASSETS'),
            'class'           => \yii\web\AssetManager::class,
            'bundles'         => [
                \yii\web\JqueryAsset::class                => [
                    'js' => []
                ],
                \yii\bootstrap\BootstrapAsset::class       => [
                    'css' => []
                ],
                \yii\bootstrap\BootstrapPluginAsset::class => [
                    'js' => []
                ]
            ],
        ],
        'request'      => [
            'enableCookieValidation' => true,
            'enableCsrfValidation'   => false,
            'cookieValidationKey'    => 'wKm_9vddBLRVD_RO70a13LY23C7NP6ny',
        ],
        'error'        => [
            'class' => \yii\web\ErrorAction::class,
        ],
        'errorHandler' => [
            'errorAction' => 'admin/error',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => require(__DIR__ . '/admin-routes.php'),
        ],
        'formatter'    => [
            'timeZone'          => 'Asia/Almaty',
            'dateFormat'        => 'dd.MM.yyyy',
            'decimalSeparator'  => false,
            'thousandSeparator' => "\x20",
            'currencyCode'      => '₸',
        ],
    ],
    'params'              => $params,
];

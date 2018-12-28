<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-api',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => [
        'log',
        'common\bootstrap\SetUp',
        [
            'class'   => 'yii\filters\ContentNegotiator',
            'formats' => [
                'text/html'        => \yii\web\Response::FORMAT_JSON,
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ]
    ],
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'subscribe' => [
            'class' => \api\modules\subscribe\SubscribeModule::class,
            'on userSubscribed' => ['common\mediators\SubscribeMediator', 'onUserSubscribed'],
        ],
        'article' => [
            'class' => \api\modules\article\ArticleModule::class,
        ],
    ],
    'components'          => [
        'request'              => [
            'parsers'          => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCsrfCookie' => false,
        ],
        'response'             => [
            'formatters'    => [
                'json' => [
                    'class'         => 'yii\web\JsonResponseFormatter',
                    'prettyPrint'   => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
            'on beforeSend' => function($event){
                /** @var \yii\web\Response $response */
                $response = $event->sender;
//                $response->headers->add('Access-Control-Allow-Origin', '*');
//                $response->headers->add('Access-Control-Request-Headers', '*');
                if ($response->data !== null) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data'    => $response->data,
                    ];
                }
            },
        ],
        'user'                 => [
            'identityClass'   => \common\entities\user\User::class,
            'enableAutoLogin' => false,
            'enableSession'   => false,
        ],
        'log'                  => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        "urlManager"           => require __DIR__ . '/api-routes.php',

        // вход через соссети
        'authClientCollection' => [
            'class'   => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class'        => \lib\helpers\authclient\VKontakte::class,
                    'clientId'     => '6475989',
                    'clientSecret' => '6jLlhsLTj5tp584hiR0g',
                ],
            ],
        ],

        // обработчик ошибок
        //        'errorHandler' => [
        //	        'class' => \lib\helpers\ErrorHandler::class,
        //        ],
    ],

    'params' => $params,
];

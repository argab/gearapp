<?php
return [
    'aliases'    => [
        '@bower'  => '@vendor/bower-asset',
        '@npm'    => '@vendor/npm-asset',
        '@upload' => '@app/../upload',
        '@photo'  => '@upload/photos',
        '@staticSite' => 'http://s.yiitest.local',
        '@staticSitePhoto' => '@staticSite/photos/',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap'  => [
        'common\bootstrap\SetUp',

    ],
    'components' => [
        'cache'       => [
            'class' => 'yii\caching\FileCache',
        ],
        //        'cache' => [
        //	        'class' => 'lib\helpers\CustomRedisCache',
        //	        'redis' => [
        //		        'hostname' => 'localhost',
        //		        'port'     => 6379,
        //		        'database' => 0,
        //	        ],
        //	        'keyPrefix'=> YII_DEBUG ? 'test_adm_' : 'adm_'
        //        ],
        'authManager' => [
            'class'           => 'yii\rbac\DbManager',
            'itemTable'       => '{{%auth_items}}',
            'itemChildTable'  => '{{%auth_item_children}}',
            'assignmentTable' => '{{%auth_assignments}}',
            'ruleTable'       => '{{%auth_rules}}',
        ],
    ],
];

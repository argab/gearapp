<?php

namespace common\bootstrap;


use yii\base\BootstrapInterface;
use yii\rbac\ManagerInterface;
use yii\web\Request;

class SetUp implements BootstrapInterface
{

    public function bootstrap($app)
    {

        $container = \Yii::$container;

        $container->setSingleton(ManagerInterface::class, function() use ($app){
            return $app->authManager;
        });

        $container->setSingleton(Request::class, function() use ($app){
            return $app->request;
        });

    }
}

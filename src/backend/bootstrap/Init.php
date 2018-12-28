<?php

namespace backend\bootstrap;

use Yii;
use yii\helpers\Url;
use yii\base\Component;

class Init extends Component
{
    public function init()
    {
        if (Yii::$app->getUser()->isGuest && Yii::$app->getRequest()->url !== Url::to(Yii::$app->getUser()->loginUrl))

            Yii::$app->getResponse()->redirect(Yii::$app->getUser()->loginUrl);

        parent::init();
    }
}

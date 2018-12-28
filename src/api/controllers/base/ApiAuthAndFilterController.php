<?php

namespace api\controllers\base;

use lib\filters\EmptyProfileFilter;
use lib\filters\EmptyRoleFilter;
use lib\filters\PhoneFilledFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class ApiAuthAndFilterController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        return ArrayHelper::merge($behaviors, [
            "authenticator" => HttpBearerAuth::class,
            PhoneFilledFilter::class,
            EmptyRoleFilter::class,
            EmptyProfileFilter::class,
        ]);
    }
}

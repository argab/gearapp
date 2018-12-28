<?php

namespace api\traits;

use common\traits\TBehavior;
use yii\filters\auth\HttpBearerAuth;

trait TApiHttpAuth
{
    protected function _behaviorConfig(): array
    {
        return ["authenticator" => HttpBearerAuth::class];
    }
}

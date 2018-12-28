<?php

namespace backend\traits;

use Yii;
use common\entities\user\User;
use common\traits\TAccessControl;

use yii\helpers\ArrayHelper;

trait TAdminAccessControl
{
    use TAccessControl;

    protected function _behaviors():array
    {
        $rules = [
            'allow' => true,
            'roles' => [User::R_ADMIN],
        ];

        $accessRules = [];

        foreach ((array) $this->_accessRules() as $k => $rule)
        {
            $accessRules[$k] = ArrayHelper::merge($rules, $rule);
        }

        return [
            'access' => [
                'rules' => $accessRules ?: [$rules]
            ]
        ];
    }
}

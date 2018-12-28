<?php

namespace api\forms\auth;

use api\forms\ApiForm;
use yii\base\Model;

class RoleForm extends ApiForm
{
    public $role;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['role', 'trim'],
            ['role', 'string'],
        ];
    }
}

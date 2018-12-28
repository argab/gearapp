<?php

namespace api\forms\auth;

use api\forms\ApiForm;
use common\entities\user\User;
use yii\base\Model;

class EmailForm extends ApiForm
{
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email', 'exist',
                'targetClass' => User::class,
                'message'     => 'There is no user with this email address.'
            ],
        ];
    }
}

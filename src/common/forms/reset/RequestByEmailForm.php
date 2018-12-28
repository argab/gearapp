<?php

namespace common\forms\reset;

use common\entities\user\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class RequestByEmailForm extends Model
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

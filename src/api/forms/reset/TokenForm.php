<?php

namespace api\forms\reset;

use api\forms\ApiForm;
use common\entities\user\User;
use yii\base\Model;

/**
 */
class TokenForm extends ApiForm
{
    public $token;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['token', 'trim'],
            ['token', 'required'],
            [
                'token',
                'exist',
                'targetClass'     => User::class,
                'targetAttribute' => 'password_reset_token',
                'message'         => 'Invalid token',
            ],

        ];
    }
}

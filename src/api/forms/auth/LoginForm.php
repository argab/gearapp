<?php

namespace api\forms\auth;

use api\forms\ApiForm;
use borales\extensions\phoneInput\PhoneInputValidator;
use common\entities\user\User;
use common\validators\PasswordValidator;
use yii\base\Model;

/**
 */
class LoginForm extends ApiForm
{
    public $phone;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['phone', 'trim'],
            ['phone', 'required'],
            [['phone'], PhoneInputValidator::class],

            [
                'phone', 'exist',
                'targetClass' => User::class,
                'message'     => 'Нет пользователья с таким телефоном',
            ],

            ['password', 'trim'],
            ['password', 'required'],
            [['password'], PasswordValidator::class],
        ];
    }
}

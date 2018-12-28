<?php

namespace api\forms\auth;

use api\forms\ApiForm;
use borales\extensions\phoneInput\PhoneInputValidator;
use common\entities\user\User;

class PhoneForm extends ApiForm
{
    public $phone;

    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_PHONE = 'phone';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['phone', 'trim'],
            ['phone', 'required'],
            [['phone'], PhoneInputValidator::class],

            // регистрация
            [
                'phone', 'unique',
                'targetClass' => User::class,
                'message'     => 'Пользователь с таким телефоном уже существует',
                'on'          => self::SCENARIO_SIGNUP
            ],

            // ввод номер телефона
            [
                'phone', 'exist',
                'targetClass' => User::class,
                'message'     => 'Нет пользователья с таким телефоном',
                'on'          => self::SCENARIO_PHONE
            ],


        ];
    }
}

<?php

namespace api\forms\auth;

use api\forms\ApiForm;
use borales\extensions\phoneInput\PhoneInputValidator;
use common\entities\user\User;

class SignUpPhoneForm extends ApiForm
{
    public $phone;


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
                'phone', 'unique',
                'targetClass' => User::class,
                'message'     => 'Вы уже зарагестрированы. Попробуйте войти с помощью пароля либо, восстановите его.'
            ],
        ];
    }
}

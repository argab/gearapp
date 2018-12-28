<?php

namespace api\forms\sms;

use api\forms\ApiForm;
use borales\extensions\phoneInput\PhoneInputValidator;
use common\entities\user\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class SmsCodeForm extends ApiForm
{
    public $code;
    public $phone;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['code', 'trim'],
            ['code', 'integer'],
            ['code', 'required'],

            ['phone', 'trim'],
            ['phone', 'required'],
            [['phone'], PhoneInputValidator::class],
            [
                'phone', 'exist',
                'targetClass' => User::class,
                'message'     => 'Нет пользователя с таким телефоном'
            ],
        ];
    }


}

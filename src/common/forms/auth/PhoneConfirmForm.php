<?php

namespace common\forms\auth;

use borales\extensions\phoneInput\PhoneInputValidator;
use common\entities\user\User;
use yii\base\Model;

class PhoneConfirmForm extends Model
{
    public $phone;
    public $code;

    const SCENARIO_SEND_SMS_TO_NEW_PHONE = 'send_sms_to_new_phone';
    const SCENARIO_SEND_SMS_TO_OLD_PHONE = 'send_sms_to_old_phone';
    const SCENARIO_CHECK_SMS_CODE = 'check_sms_code';

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
                'on'          => self::SCENARIO_SEND_SMS_TO_NEW_PHONE
            ],

            // ввод номер телефона
            [
                'phone', 'exist',
                'targetClass' => User::class,
                'message'     => 'Нет пользователья с таким телефоном',
                'on'          => self::SCENARIO_SEND_SMS_TO_OLD_PHONE
            ],

            ['code', 'trim'],
            ['code', 'integer'],
            ['code', 'required', 'on' => self::SCENARIO_CHECK_SMS_CODE],
        ];
    }
}

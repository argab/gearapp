<?php

namespace common\forms\auth;

use borales\extensions\phoneInput\PhoneInputValidator;
use common\entities\user\User;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
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
                'message'     => 'There is no user with this phone.'
            ],
        ];
    }


}

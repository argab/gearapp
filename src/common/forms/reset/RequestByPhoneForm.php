<?php

namespace common\forms\reset;

use borales\extensions\phoneInput\PhoneInputValidator;
use Yii;
use yii\base\Model;
use common\entities\user\User;

/**
 * Password reset request form
 */
class RequestByPhoneForm extends Model
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
                'phone', 'exist',
                'targetClass' => User::class,
                'message'     => 'There is no user with this phone.'
            ],
        ];
    }


}

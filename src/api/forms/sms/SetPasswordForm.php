<?php

namespace api\forms\sms;

use api\forms\ApiForm;
use borales\extensions\phoneInput\PhoneInputValidator;
use common\entities\user\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class SetPasswordForm extends ApiForm
{
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'trim'],
            ['password', 'string', 'min' => 6],
            ['password', 'required'],
        ];
    }


}

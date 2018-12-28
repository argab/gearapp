<?php

namespace common\forms\reset;

use yii\base\Model;

/**
 * Password reset request form
 */
class RequestByPhoneFormSetPassword extends Model
{
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }


}

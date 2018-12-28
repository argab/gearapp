<?php

namespace common\validators;

use api\forms\auth\LoginForm;
use common\entities\user\User;
use yii\validators\Validator;

class PasswordValidator extends Validator
{

    /**
     * @param LoginForm $form
     * @param string $attribute
     */
    public function validateAttribute($form, $attribute)
    {
        if ($form->phone)
        {
            $user = User::findByPhone($form->phone);
            if ($user)
            {
                if ($user->validatePassword($form->password))
                {
                    return true;
                }
            }
        }
        $this->addError($form, $attribute, 'Не верный пароль');
    }

}
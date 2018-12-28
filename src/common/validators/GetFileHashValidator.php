<?php

namespace common\validators;

use api\forms\auth\LoginForm;
use common\entities\user\User;
use lib\services\file\FileService;
use yii\validators\Validator;

class GetFileHashValidator extends Validator
{

    public function validateAttribute($form, $attribute)
    {
        if ($form->file)
        {
            $temp = $form->file->tempName;
            if (file_exists($temp))
            {
                return $form->hash = FileService::getHashByFile($temp);
            }
        }


        //		if($form->file){
        //			$user = User::findByPhone($form->phone);
        //			if($user){
        //				if($user->validatePassword($form->password)){
        //					return true;
        //				}
        //			}
        //		}
        //		$this->addError($form, $attribute, 'Не верный пароль');
    }

}
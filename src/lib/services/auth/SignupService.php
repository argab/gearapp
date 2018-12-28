<?php

namespace lib\services\auth;


use api\forms\auth\PhoneForm;
use common\entities\user\User;

class SignupService
{
    /**
     * @param $phone
     *
     * @return User
     */
    public function signup($phone): User
    {
        $user = User::create($phone);

        if ( ! $user->save())
            throw new \RuntimeException('Saving error');

        return $user;
    }
}
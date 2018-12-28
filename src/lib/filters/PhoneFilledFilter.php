<?php

namespace lib\filters;

use api\exceptions\Http400Exception;
use common\entities\user\User;
use yii\base\ActionFilter;
use yii\web\HttpException;

class PhoneFilledFilter extends ActionFilter
{
    /**
     * @param \yii\base\Action $action
     *
     * @return bool
     * @throws Http400Exception
     */
    public function beforeAction($action)
    {
        /** @var User $user */
        $user = User::authUser();

        if ( ! $user)
            return false;

        if (empty($user->phone))
            throw new Http400Exception('Use phone is empty');

        return parent::beforeAction($action);
    }


}
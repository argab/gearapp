<?php

namespace lib\filters;

use api\exceptions\Http400Exception;
use common\entities\user\User;
use yii\base\ActionFilter;
use yii\web\HttpException;

class EmptyRoleFilter extends ActionFilter
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

        if ($user->isRoleEmpty())
            throw new Http400Exception('Set user role');

        return parent::beforeAction($action);
    }


}
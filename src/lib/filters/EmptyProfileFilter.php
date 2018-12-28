<?php

namespace lib\filters;

use api\exceptions\Http400Exception;
use common\entities\user\User;
use lib\services\user\UserProfileService;
use yii\base\ActionFilter;
use yii\web\HttpException;

class EmptyProfileFilter extends ActionFilter
{
    /**
     * @var UserProfileService
     */
    private $service;

    public function __construct(UserProfileService $service, array $config = [])
    {
        parent::__construct($config);
        $this->service = $service;
    }

    /**
     * @param \yii\base\Action $action
     * @param UserProfileService $service
     *
     * @return bool
     * @throws Http400Exception
     */
    public function beforeAction($action)
    {
        /** @var User $user */
        $user = User::authUser();

        $this->service->checkIfProfileNotEmpty($user);
        //
        //		if(!$user)
        //			return false;
        //
        //		if($user->isRoleEmpty())
        //			throw new Http400Exception('Set user role');

        return parent::beforeAction($action);
    }


}
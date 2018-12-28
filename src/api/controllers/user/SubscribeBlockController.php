<?php

namespace api\controllers\user;

use api\exceptions\Http400Exception;
use common\entities\team\Team;
use common\entities\user\User;
use lib\helpers\Response;
use lib\services\subscribe\SubscribeService;
use yii\rest\Controller;
use api\modules\subscribe\SubscribeModule;
use api\traits\TApiRestController;
use api\traits\TApiProfileHttpAuth;

/* @property SubscribeModule $module */
class SubscribeBlockController extends Controller
{
    use TApiRestController, TApiProfileHttpAuth;


    /**
     * @param $subscriber_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     * @throws \api\exceptions\NotFoundException
     */
    public function actionBlockSubscriber($subscriber_id)
    {
        User::findByIdOrFail($subscriber_id);
        $user = User::authUser();

        if ( ! $item = SubscribeService::checkIfUserSubscribedToUser($user->id, $subscriber_id))
            throw new Http400Exception('Пользователь не найден');

        $item->setBlock();
        $item->saveOrFail();

        return $this->response->success([]);
    }


    /**
     * @param $subscriber_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     * @throws \api\exceptions\NotFoundException
     */
    public function actionUnblockSubscriber($subscriber_id)
    {
        User::findByIdOrFail($subscriber_id);
        $user = User::authUser();

        if ( ! $item = SubscribeService::checkIfUserSubscribedToUser($user->id, $subscriber_id))
            throw new Http400Exception('Пользователь не найден');

        $item->setActive();
        $item->saveOrFail();

        return $this->response->success([]);
    }

}

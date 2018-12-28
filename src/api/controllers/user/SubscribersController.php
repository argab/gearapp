<?php

namespace api\controllers\user;

use api\exceptions\Http400Exception;
use api\modules\subscribe\entity\Subscribe;
use common\entities\team\Team;
use common\entities\user\User;
use lib\helpers\Response;
use lib\services\subscribe\SubscribeService;
use yii\rest\Controller;
use api\modules\subscribe\SubscribeModule;
use api\traits\TApiRestController;
use api\traits\TApiProfileHttpAuth;

/*
 * Подписчики
 */
class SubscribersController extends Controller
{
    use TApiRestController, TApiProfileHttpAuth;




    /**
     * @param $user_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\NotFoundException
     */
    public function actionUserSubscribers($user_id)
    {
        $item = User::findByIdOrFail($user_id);

        return Response::responseItems([
            User::serialize($item->subscribers)
        ]);

    }



    /**
     * @param $team_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\NotFoundException
     */
    public function actionTeamSubscribers($team_id)
    {
        $item = Team::findByIdOrFail($team_id);

        return Response::responseItems([
            User::serialize($item->subscribers)
        ]);
    }

    public function actionUserSubscribersCount($user_id)
    {
        $item = User::findByIdOrFail($user_id);

        return Response::success([
            'count' => count($item->subscribers)
        ]);
    }

    public function actionTeamSubscribersCount($team_id)
    {
        $item = Team::findByIdOrFail($team_id);

        return Response::success([
            'count' => count($item->subscribers)
        ]);
    }


    /**
     * @param $user_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     * @throws \api\exceptions\NotFoundException
     */
    public function actionBlockUser($user_id)
    {
        User::findByIdOrFail($user_id);
        $user = User::authUser();

        if ($user->id == $user_id)
            throw new Http400Exception('Нельязя заблокировать самого себя');

        if ($item = SubscribeService::checkIfUserSubscribedToUser($user_id, $user->id))
            throw new Http400Exception('Пользователь уже подписан');

        $item->setBlock();
        $item->saveOrFail();

        return $this->response->success([]);
    }

    /**
     * @param $user_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     * @throws \api\exceptions\NotFoundException
     */
    public function actionUnblockUser($user_id)
    {
        User::findByIdOrFail($user_id);
        $user = User::authUser();

        if ($user->id == $user_id)
            throw new Http400Exception('Нельязя заблокировать самого себя');

        if ($item = SubscribeService::checkIfUserSubscribedToUser($user_id, $user->id))
            throw new Http400Exception('Пользователь уже подписан');

        $item->setActive();
        $item->saveOrFail();

        return $this->response->success([]);
    }

}

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
 * Подписки
 */
class SubscriptionsController extends Controller
{
    use TApiRestController, TApiProfileHttpAuth;

    // подписки к пользователям
    public function actionUser()
    {
        $item = User::authUser();

        if($this->request->get('count'))
            return Response::count(count($item->userSubscriptions));

        return Response::responseItems([
            User::serialize($item->userSubscriptions)
        ]);
    }

    public function actionUserById($user_id)
    {
        $item = User::findByIdOrFail($user_id);

        if($this->request->get('count'))
            return Response::count(count($item->userSubscriptions));

        return Response::responseItems([
            User::serialize($item->userSubscriptions)
        ]);
    }


    // подписки к командам
    public function actionTeam()
    {
        $item = User::authUser();

        if($this->request->get('count'))
            return Response::count(count($item->teamSubscriptions));

        return Response::responseItems([
            Team::serialize($item->teamSubscriptions)
        ]);
    }

    public function actionTeamById($user_id)
    {
        $item = User::findByIdOrFail($user_id);

        if($this->request->get('count'))
            return Response::count(count($item->teamSubscriptions));

        return Response::responseItems([
            Team::serialize($item->teamSubscriptions)
        ]);
    }


    // подписаться
    public function actionSubscribeToUser($user_id)
    {
        User::findByIdOrFail($user_id);
        $user = User::authUser();

        if ($user->id == $user_id)
            throw new Http400Exception('Нельязя подписаться на самого себя');

        if (SubscribeService::checkIfUserSubscribedToUser($user_id, $user->id))
            throw new Http400Exception('Вы уже подписались к этому пользователю');

        SubscribeService::subscribeToUser($user->id, $user_id);

        return $this->response->success([]);

    }

    public function actionSubscribeToTeam($team_id)
    {
        Team::findByIdOrFail($team_id);
        $user = User::authUser();

        if (SubscribeService::checkIfUserSubscribedToTeam($team_id, $user->id))
            throw new Http400Exception('Вы подписаны на эту команду');

        SubscribeService::subscribeUserToTeam($user->id, $team_id);

        return $this->response->success([]);
    }


    // отписаться
    public function actionUnsubscribeFromUser($user_id)
    {
        User::findByIdOrFail($user_id);
        $user = User::authUser();

        if ($user->id == $user_id)
            throw new Http400Exception('Нельзя отписаться от самого себя');

        if ( ! SubscribeService::checkIfUserSubscribedToUser($user_id, $user->id))
            throw new Http400Exception('Пользователь не подписан');

        SubscribeService::unsubscribeUserFromUser($user_id, $user->id);

        return $this->response->success([]);
    }

    public function actionUnsubscribeFromTeam($team_id)
    {
        Team::findByIdOrFail($team_id);
        $user = User::authUser();

        if (!SubscribeService::checkIfUserSubscribedToTeam($team_id, $user->id))
            throw new Http400Exception('Вы не подписаны на эту команду');

        SubscribeService::unsubscribeUserFromTeam($user->id, $team_id);

        return $this->response->success([]);
    }

}

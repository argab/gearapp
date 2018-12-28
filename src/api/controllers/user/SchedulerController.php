<?php

namespace api\controllers\user;

use api\exceptions\Http400Exception;
use api\traits\TApiHttpAuth;
use common\entities\user\User;
use api\traits\TApiRestController;
use yii\rest\Controller;

class SchedulerController extends Controller
{
    use TApiRestController, TApiHttpAuth;

    /**
     * @param $user_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\NotFoundException
     */
    public function actionCheckUser($user_id)
    {
        $user = User::findByIdOrFail($user_id);

        return $this->response->responseItem([
            'online'      => $user->onlineCheck(),
            'last_online' => $user->last_online,
        ]);
    }

    public function actionCheck()
    {
        $user = User::authUser();

        return $this->response->responseItem([
            'online'      => $user->onlineCheck(),
            'last_online' => $user->last_online,
        ]);
    }

    /**
     * @throws Http400Exception
     */
    public function actionSetOnline()
    {
        $user = User::authUser();
        $user->onlineSet();
        $user->saveOrFail();

        return $this->response->success([]);
    }


}

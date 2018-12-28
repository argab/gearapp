<?php

namespace api\controllers\auth;

use lib\services\auth\AuthNetworkService;
use common\entities\user\Auth;
use common\entities\user\Token;
use yii\rest\Controller;

class NetworkAuthController extends Controller
{

    public function actions()
    {
        return [
            'auth' => [
                'class'           => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * @param $client
     *
     * @return mixed|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source'    => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        $service = new AuthNetworkService($auth, $attributes, $client);

        if ($user = $service->login())
        {
            return Token::createTokenAndSuccessResponse($user);
        }

        $user = $service->registration();

        return Token::createTokenAndSuccessResponse($user);

    }

}

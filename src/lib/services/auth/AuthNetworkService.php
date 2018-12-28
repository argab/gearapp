<?php

namespace lib\services\auth;


use api\exceptions\Http400Exception;
use common\entities\user\Auth;
use common\entities\user\User;
use SebastianBergmann\Timer\RuntimeException;
use Yii;

class AuthNetworkService
{
    public $auth;
    public $attributes;
    public $client;

    /**
     * AuthNetworkService constructor.
     *
     * @param $auth
     * @param array $attributes
     * @param $client
     */
    public function __construct($auth, $attributes = [], $client)
    {
        $this->auth = $auth;
        $this->attributes = $attributes;
        $this->client = $client;
    }

    /**
     * @return bool
     */
    public function login()
    {
        if ( ! $this->auth)
            return false;

        if ( ! $user = $this->auth->user)
            return false;

        return $user;
    }

    /**
     * @return User
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function registration(): User
    {
        if (isset($this->attributes['email']) && User::find()->where(['email' => $this->attributes['email']])->exists())
            throw new Http400Exception(sprintf('Пользователь с такой электронной почтой %s уже существует', $this->attributes['email']));

        $user = new User([
            'username' => $this->attributes['login'],
            'email'    => $this->attributes['email'],
            'password' => Yii::$app->security->generateRandomString(12),
        ]);
        $user->generateAuthKey();
        $user->generatePasswordResetToken();
        $transaction = $user->getDb()->beginTransaction();

        if ( ! $user->save())
            throw new RuntimeException($user->getErrors());

        $auth = new Auth([
            'user_id'   => $user->id,
            'source'    => $this->client->getId(),
            'source_id' => (string) $this->attributes['id'],
        ]);

        if ( ! $auth->save())
            throw new RuntimeException($auth->getErrors());

        $transaction->commit();


        return $user;

    }

}
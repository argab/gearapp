<?php

namespace common\entities\user;

use lib\helpers\Response;
use common\entities\user\User;
use Yii;

/**
 * This is the model class for table "tokens".
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $expire_time
 * @property User $id0
 */
class Token extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tokens';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token'], 'required'],
            [['user_id'], 'integer'],
            [['expire_time'], 'safe'],
            [['token'], 'string', 'max' => 255],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'user_id'     => 'User ID',
            'token'       => 'Token',
            'expire_time' => 'Time',
        ];
    }

    public static function clearOldTokens()
    {
        self::deleteAll('expire_time < ' . time());
    }

    public static function createToken($user)
    {
        self::clearOldTokens();
        $token = new Token();
        $token->token = Yii::$app->getSecurity()->generateRandomString(30);
        $token->user_id = $user->id;
        $token->expire_time = time() + 3600 * 24 * 7;

        if ( ! $token->save())
            throw new \RuntimeException('Save error');

        return $token;
    }

    /**
     * @param $user User
     *
     * @return mixed
     */
    public static function createTokenAndSuccessResponse($user)
    {
        $token = Token::createToken($user);

        return Response::successCreated([
            'phone'  => $user->phone,
            'token'  => $token->token,
            'expire' => $token->expire_time
        ]);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @param $token
     *
     * @return mixed
     */
    public static function getUserByToken($token): User
    {
        $token = self::find()
            ->where(['token' => $token])
            ->andWhere(['>', 'expire_time', time()])
            ->one();

        if ( ! $token)
            throw new \DomainException('Token not found');

        if ( ! $user = $token->user)
            throw new \DomainException('User not found');

        return $user;
    }
}

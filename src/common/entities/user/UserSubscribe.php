<?php

namespace common\entities\user;

use api\traits\TApiModel;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_subscribe".
 *
 * @property int $id
 * @property int $subscriber_id Подписчик
 * @property int $user_id К кому подписка
 * @property int $team_id К кому подписка
 * @property int $event_id К кому подписка
 * @property int $is_active
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $subscriber
 */
class UserSubscribe extends \yii\db\ActiveRecord
{
    use TApiModel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_subscribe';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subscriber_id', 'user_id', 'team_id', 'event_id', 'is_active', 'created_at', 'updated_at'], 'integer'],
            [['subscriber_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['subscriber_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subscriber_id' => 'Subscriber ID',
            'user_id' => 'User ID',
            'team_id' => 'Team ID',
            'event_id' => 'Event ID',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriber()
    {
        return $this->hasOne(User::class, ['id' => 'subscriber_id']);
    }


    public static function findBySubscriberIdAndWhere($subscriber_id, array $where)
    {
        return self::find()
            ->where(['subscriber_id' => $subscriber_id])
            ->andWhere($where)
            ->limit(1)
            ->one();
    }

    public static function subscribeToTeam($subscriber_id, $team_id)
    {
        return self::createWithoutSave([
            'subscriber_id' => $subscriber_id,
            'team_id'       => $team_id
        ]);
    }

    public static function subscribeToUser($subscriber_id, $user_id)
    {
        return self::createWithoutSave([
            'subscriber_id' => $subscriber_id,
            'user_id'       => $user_id
        ]);
    }


    public function setBlock()
    {
        $this->is_active = 0;
    }

    public function setActive()
    {
        $this->is_active = 1;
    }
}

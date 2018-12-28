<?php

namespace api\modules\subscribe\entity;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

use api\traits\TApiModel;
use common\entities\user\User;
use common\entities\team\Team;

class Subscribe extends ActiveRecord
{
    use TApiModel;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_subscribe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            ['subscribe_to_user', 'exists', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['subscribe_to_team', 'exists', 'targetClass' => Team::class, 'targetAttribute' => 'id'],
        ];
    }

    public function subscribe()
    {
        $this->user_id = Yii::$app->user->getId();

        $this->save();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriber()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function serializeItem($item)
    {
        return [
            'id'      => $item->id,
            'title'   => $item->title,
            'creator' => User::serializeItem($item->subscriber),
        ];
    }

}

<?php

namespace common\entities;

use common\base\Assert;
use common\entities\user\User;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "counter".
 * @property int $id
 * @property string $model
 * @property string $model_id
 * @property int $type
 * @property string $user_id
 * @property string $created_at
 */
class Counter extends \yii\db\ActiveRecord
{
    const TYPE_VIEW = 1;
    const TYPE_LIKE = 2;
    const TYPE_SHARE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'counter';
    }

    private static function findByModel($model, $type)
    {
        return self::find()->where([
            'model'    => get_class($model),
            'model_id' => $model->id,
            'user_id'  => User::authUser()->id,
            'type'     => $type,
        ])->limit(1)->one();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_id', 'type', 'user_id', 'created_at'], 'integer'],
            [['model'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'model'      => 'Model',
            'model_id'   => 'Model ID',
            'type'       => 'Type',
            'user_id'    => 'User ID',
            'created_at' => 'Created At',
        ];
    }


    private static function create(Model $model, $type)
    {
        $item = new self([
            'model'      => get_class($model),
            'model_id'   => $model->id,
            'user_id'    => User::authUser()->id,
            'type'       => $type,
            'created_at' => time(),
        ]);
        Assert::true($item->save());
    }

    private static function remove(Model $model, $type)
    {
        $item = self::find()->where([
            'model'    => get_class($model),
            'model_id' => $model->id,
            'user_id'  => User::authUser()->id,
            'type'     => $type,
        ])->limit(1)->one();

        Assert::notFound($item);

        Assert::isRemoved($item->delete());
    }



    public static function setLike($model)
    {
        Assert::alreadyExist(self::findByModel($model, self::TYPE_LIKE));
        self::create($model, self::TYPE_LIKE);
    }

    public static function setDislike($model)
    {
        self::remove($model, self::TYPE_LIKE);
    }

    public static function setView($model)
    {
        self::create($model, self::TYPE_VIEW);
    }

    public static function deleteAllByModel($model)
    {
        self::deleteAll([
            'model'    => get_class($model),
            'model_id' => $model->id,
        ]);
    }
}

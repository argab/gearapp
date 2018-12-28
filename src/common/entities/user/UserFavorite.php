<?php

namespace common\entities\user;

use common\base\Assert;
use Yii;

/**
 * This is the model class for table "user_favorite".
 * @property int $id
 * @property int $user_id
 * @property string $model
 * @property int $model_id
 * @property User $user
 */
class UserFavorite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_favorite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'model_id'], 'integer'],
            [['model'], 'string', 'max' => 30],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'user_id'  => 'User ID',
            'model'    => 'Model',
            'model_id' => 'Model ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function create($model)
    {
        $item = new self([
            'user_id'  => User::authUser()->id,
            'model'    => get_class($model),
            'model_id' => $model->id
        ]);

        Assert::true($item->save());
    }

    public static function findByModel($model)
    {
        $item = self::find()->where([
            'user_id'  => User::authUser()->id,
            'model'    => get_class($model),
            'model_id' => $model->id
        ])->limit(1)->one();

        return $item;
    }

    public static function remove($model)
    {
        $item = self::findByModel($model);

        Assert::notFound($item);

        Assert::isRemoved($item->delete());
    }

    public static function toFavorite($model)
    {
        Assert::alreadyExist(self::findByModel($model));
        self::create($model);
    }

    public static function unFavorite($model)
    {
        Assert::notExist(self::findByModel($model));
        self::remove($model);
    }

}

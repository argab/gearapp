<?php

namespace common\entities\file;

use common\base\Assert;
use Yii;

/**
 * This is the model class for table "photos".
 * @property int $id
 * @property int $file_id
 * @property string $model
 * @property int $model_id
 * @property string $created_at
 * @property Files $file
 */
class Photos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id'], 'required'],
            [['file_id', 'model_id'], 'integer'],
            [['created_at'], 'safe'],
            [['model'], 'string', 'max' => 30],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['file_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'file_id'    => 'File ID',
            'model'      => 'Model',
            'model_id'   => 'Model ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(Files::className(), ['id' => 'file_id']);
    }

    public static function deleteByModelAndId($model_class, $model_id)
    {
        self::deleteAll([
            'model'    => $model_class,
            'model_id' => $model_id
        ]);
    }

    public static function findBy($model_class, $model_id, $photo_id)
    {
        return self::find()->where([
            'model'    => $model_class,
            'model_id' => $model_id,
            'file_id'  => $photo_id
        ])->limit(1)->one();
    }

    public static function createByModelAndId($model_class, $model_id, $photo_id)
    {
        if ( ! self::findBy($model_class, $model_id, $photo_id))
        {
            $item = new self([
                'model'    => $model_class,
                'model_id' => $model_id,
                'file_id'  => $photo_id
            ]);
            Assert::save($item->save());

            return $item;
        }
    }
}

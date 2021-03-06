<?php

namespace common\entities\car;

use api\traits\TApiModel;
use Yii;

/**
 * This is the model class for table "car_class".
 *
 * @property int $id
 * @property string $title
 *
 * @property Car[] $cars
 */
class CarClass extends \yii\db\ActiveRecord
{
    use TApiModel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_class';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCars()
    {
        return $this->hasMany(Car::className(), ['cc_id' => 'id']);
    }
}

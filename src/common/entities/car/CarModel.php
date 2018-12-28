<?php

namespace common\entities\car;

use api\traits\TApiModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "car_model".
 *
 * @property int $id
 * @property int $cb_id
 * @property string $title
 *
 * @property Car[] $cars
 * @property CarBrand $cb
 */
class CarModel extends \yii\db\ActiveRecord
{
    use TApiModel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_model';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cb_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['cb_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarBrand::class, 'targetAttribute' => ['cb_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cb_id' => 'Cb ID',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCars()
    {
        return $this->hasMany(Car::class, ['cb_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCb()
    {
        return $this->hasOne(CarBrand::class, ['id' => 'cb_id']);
    }

	public static function serializeItem($item, $params = [])
	{
		$result = ArrayHelper::toArray($item);
//		ArrayHelper::remove($result, 'cb_id');

//		if(in_array('full', $params))
//			$result['brand'] = CarBrand::serializeItem($item->cb);

		return $result;
	}
}

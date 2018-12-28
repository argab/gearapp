<?php

namespace common\entities\car;

use api\traits\TApiModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "car_brand".
 *
 * @property int $id
 * @property string $title
 *
 * @property Car[] $cars
 * @property CarModel[] $carModels
 */
class CarBrand extends \yii\db\ActiveRecord
{
    use TApiModel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_brand';
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
        return $this->hasMany(Car::class, ['cb_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarModels()
    {
        return $this->hasMany(CarModel::class, ['cb_id' => 'id']);
    }

	public static function serializeItem($item, $params = [])
	{
		$result = ArrayHelper::toArray($item);
		if (in_array('full', $params)) {
			$result['models'] = CarModel::serialize($item->carModels);
		}

		return $result;
	}
}

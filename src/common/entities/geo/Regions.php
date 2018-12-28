<?php

namespace common\entities\geo;

use api\traits\TApiModel;
use common\base\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "regions".
 * @property int $region_id
 * @property int $country_id
 * @property string $title_ru
 * @property string $title_en
 * @property Cities[] $cities
 * @property Countries $country
 */
class Regions extends ActiveRecord
{
    use TApiModel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id'], 'required'],
            [['country_id'], 'integer'],
            [['title_ru', 'title_en'], 'string', 'max' => 150],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::class, 'targetAttribute' => ['country_id' => 'country_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'region_id'  => 'Region ID',
            'country_id' => 'Country ID',
            'title_ru'   => 'Title Ru',
            'title_en'   => 'Title En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(Cities::class, ['region_id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::class, ['country_id' => 'country_id']);
    }

    public static function search($params)
    {
        $items = self::find()->with('country')->limit(10);
	    $items = self::andFilterWhereLikeByParams($items, $params);
        $items->orderBy(['title_ru' => SORT_ASC]);

        return $items->all();
    }


    public static function serializeItem($item)
    {
        return [
            "region_id" => $item->region_id,
            "title_ru"  => $item->title_ru,
            "title_en"  => $item->title_en,
//            'country'   => Countries::serialize($item->country)
        ];

    }
}

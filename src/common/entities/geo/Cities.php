<?php

namespace common\entities\geo;

use api\traits\TApiModel;
use common\base\ActiveRecord;
use common\entities\user\UserProfile;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cities".
 * @property int $city_id
 * @property int $country_id
 * @property int $important
 * @property int $region_id
 * @property string $title_ru
 * @property string $area_ru
 * @property string $region_ru
 * @property string $title_en
 * @property string $area_en
 * @property string $region_en
 * @property Countries $country
 * @property Regions $region
 * @property UserProfile[] $userProfiles
 */
class Cities extends ActiveRecord
{
    use TApiModel;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'important'], 'required'],
            [['country_id', 'important', 'region_id'], 'integer'],
            [['title_ru', 'area_ru', 'region_ru', 'title_en', 'area_en', 'region_en'], 'string', 'max' => 150],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::class, 'targetAttribute' => ['country_id' => 'country_id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::class, 'targetAttribute' => ['region_id' => 'region_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'city_id'    => 'City ID',
            'country_id' => 'Country ID',
            'important'  => 'Important',
            'region_id'  => 'Region ID',
            'title_ru'   => 'Title Ru',
            'area_ru'    => 'Area Ru',
            'region_ru'  => 'Region Ru',
            'title_en'   => 'Title En',
            'area_en'    => 'Area En',
            'region_en'  => 'Region En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::class, ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::class, ['region_id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::class, ['city_id' => 'city_id']);
    }

    public static function search($params)
    {
        $items = self::find()->with('region', 'country')->limit(10);
        $items = self::andFilterWhereLikeByParams($items, $params);
        $items->orderBy(['title_ru' => SORT_ASC]);

        return $items->all();
    }


    public static function serializeItem($item)
    {
        return [
            'city_id'  => $item->city_id,
            'country_id'  => $item->country_id,
            'region_id'  => $item->region_id,
            'title_ru' => $item->title_ru,
            'title_en' => $item->title_en,
//            'country'  => Countries::serialize($item->country),
            //            'region'   => Regions::serialize($item->region),
        ];
    }


}

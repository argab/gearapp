<?php

namespace common\entities\geo;

use api\traits\TApiModel;
use common\base\ActiveRecord;
use Yii;

/**
 * This is the model class for table "countries".
 * @property int $country_id
 * @property string $title_ru
 * @property string $title_en
 * @property Cities[] $cities
 * @property Regions[] $regions
 * @property UserProfile[] $userProfiles
 */
class Countries extends ActiveRecord
{
    use TApiModel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_ru', 'title_en'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
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
        return $this->hasMany(Cities::class, ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasMany(Regions::class, ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::class, ['country_id' => 'country_id']);
    }

    public static function search($params)
    {
        $items = self::find()->limit(10);
	    $items = self::andFilterWhereLikeByParams($items, $params);
        $items->orderBy(['title_ru' => SORT_ASC]);

        return $items->all();
    }

}

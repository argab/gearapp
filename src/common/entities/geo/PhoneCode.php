<?php

namespace common\entities\geo;

use api\traits\TApiModel;
use Yii;

/**
 * This is the model class for table "countries_phone_code".
 *
 * @property int $id
 * @property int $phone
 * @property string $country_name
 * @property string $country_code
 */
class PhoneCode extends \yii\db\ActiveRecord
{
    use TApiModel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'countries_phone_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'integer'],
            [['country_name'], 'string', 'max' => 50],
            [['country_code'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'country_name' => 'Country Name',
            'country_code' => 'Country Code',
        ];
    }

    /**
     * @param $params
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function search($params)
    {
        $items = self::find()->limit(10);
        $items = self::andFilterWhereLikeByParams($items, $params);
        $items->orderBy(['country_name' => SORT_ASC]);
        return $items->all();
    }


    public static function serializeItem($item)
    {
        return [
            "phone" => $item->phone,
            "country_name"  => $item->country_name,
            "country_code"  => $item->country_code,
        ];

    }
}

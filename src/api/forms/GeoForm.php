<?php

namespace api\forms;

use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use yii\base\Model;

class GeoForm extends Model
{
    public $country_id;
    public $region_id;
    public $city_id;

    public function rules(): array
    {
        return [
            [['city_id', 'country_id','region_id'], 'integer'],
            [['city_id', 'country_id','region_id'], 'required'],
            [['city_id'], 'exist', 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'city_id']],
            [['country_id'], 'exist', 'targetClass' => Countries::class, 'targetAttribute' => ['country_id' => 'country_id']],
            [['region_id'], 'exist', 'targetClass' => Regions::class, 'targetAttribute' => ['region_id' => 'region_id']],
        ];
    }


}

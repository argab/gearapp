<?php

namespace common\forms\event;

use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\user\User;
use yii\base\Model;

class SearchEventForm extends Model
{
    public $event_date_start;
    public $event_date_end;
    public $city_id;
    public $country_id;
    public $region_id;
//    public $owner_ids;
    public $favorite;
    public $archive;
    public $history;

    
    public function rules()
    {
        return [
            [['event_date_start', 'event_date_end'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            
            [['city_id', 'country_id', 'region_id'], 'integer'],
            
            [
                'country_id',
                'exist',
                'targetClass'     => Countries::class,
                'targetAttribute' => 'country_id',
                'message'         => 'Укажите страну из списка',
            ],
            
            [
                'city_id',
                'exist',
                'targetClass'     => Cities::class,
                'targetAttribute' => 'city_id',
                'message'         => 'Укажите город из списка',
            ],
            
            [
                'region_id',
                'exist',
                'targetClass'     => Regions::class,
                'targetAttribute' => 'region_id',
                'message'         => 'Укажите регион из списка',
            ],
            
//            ['owner_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
            
            ['favorite', 'boolean'],
            ['archive', 'boolean'],
            ['history', 'boolean'],

        ];
    }
}

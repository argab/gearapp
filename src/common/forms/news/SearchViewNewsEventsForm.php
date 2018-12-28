<?php

namespace common\forms\news;

use common\dictionaries\Role;
use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\team\Team;
use common\entities\user\User;
use yii\base\Model;

class SearchViewNewsEventsForm extends Model
{
//    public $date_from;
//    public $date_to;
//    public $city_id;
//    public $country_id;
//    public $region_id;
//    public $owner_ids;
//    public $role;
//    public $favorite;
//    public $archive;
    public $history;

    
    public function rules()
    {
        return [
//            [['date_from', 'date_to'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
//
//            [['city_id', 'country_id', 'region_id'], 'integer'],
//
//            [
//                'country_id',
//                'exist',
//                'targetClass'     => Countries::class,
//                'targetAttribute' => 'country_id',
//                'message'         => 'Укажите страну из списка',
//            ],
//
//            [
//                'city_id',
//                'exist',
//                'targetClass'     => Cities::class,
//                'targetAttribute' => 'city_id',
//                'message'         => 'Укажите город из списка',
//            ],
//
//            [
//                'region_id',
//                'exist',
//                'targetClass'     => Regions::class,
//                'targetAttribute' => 'region_id',
//                'message'         => 'Укажите регион из списка',
//            ],
//
//            ['owner_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
//
//            ['role', 'in', 'range' => Role::keys()],
//
//            [['owner_ids'], 'default', 'value' => []],

//            ['favorite', 'boolean'],
//            ['archive', 'boolean'],
            ['history', 'boolean'],

        ];
    }
}

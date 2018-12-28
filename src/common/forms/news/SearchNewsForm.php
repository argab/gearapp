<?php

namespace common\forms\news;

use common\dictionaries\Role;
use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\team\Team;
use common\entities\user\User;
use yii\base\Model;

class SearchNewsForm extends Model
{
    public $post_date_from;
    public $post_date_to;
    public $city_id;
    public $country_id;
    public $region_id;
    public $team_ids;
    public $owner_ids;
    public $role;
    public $favorite;
    public $archive;
    public $history;

    
    public function rules()
    {
        return [
            [['post_date_from', 'post_date_to'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            
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
            
            ['team_ids', 'each', 'rule' => ['exist', 'targetClass' => Team::class, 'targetAttribute' => 'id']],
            
            ['owner_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
            
            ['role', 'in', 'range' => Role::keys()],


            [['team_ids','owner_ids'], 'default', 'value' => []],

            ['favorite', 'boolean'],
            ['archive', 'boolean'],
            ['history', 'boolean'],

        ];
    }
}

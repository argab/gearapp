<?php

namespace api\forms\user\team;

use api\forms\ApiForm;
use common\entities\file\Files;
use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\team\Team;

class TeamForm extends ApiForm
{
    const SCENARIO_UPDATE = 'update';

    public $title;
    public $id;
    public $photo_id;

    public $country_id;
    public $city_id;
    public $region_id;
    public $description;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['title', 'trim'],
            ['title', 'required'],
            ['title', 'string', 'max' => 255],

            [
                'title',
                'unique',
                'targetClass'     => Team::class,
                'targetAttribute' => 'title',
                'message'         => 'Такое название уже есть',
                'except' => self::SCENARIO_UPDATE
            ],

            [
                'id',
                'exist',
                'targetClass'     => Team::class,
                'targetAttribute' => 'id',
                'message'         => 'Нет такого id',
            ],

            ['photo_id', 'integer'],
            [
                'photo_id',
                'exist',
                'targetClass'     => Files::class,
                'targetAttribute' => 'id',
                'message'         => 'Загрузите изображение в photo/upload и укажите возвращенный id',
            ],

            ['country_id', 'integer'],
            ['country_id', 'required'],
            [
                'country_id',
                'exist',
                'targetClass'     => Countries::class,
                'targetAttribute' => 'country_id',
                'message'         => 'Укажите страну из списка',
            ],

            ['city_id', 'integer'],
            ['city_id', 'required'],
            [
                'city_id',
                'exist',
                'targetClass'     => Cities::class,
                'targetAttribute' => 'city_id',
                'message'         => 'Укажите город из списка',
            ],

            ['region_id', 'integer'],
            ['region_id', 'required'],
            [
                'region_id',
                'exist',
                'targetClass'     => Regions::class,
                'targetAttribute' => 'region_id',
                'message'         => 'Укажите регион из списка',
            ],

            ['description', 'trim'],
            ['description', 'string', 'max' => 340],

        ];
    }
}

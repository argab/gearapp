<?php

namespace api\forms\user\profile;

use api\forms\ApiForm;
use common\entities\file\Files;
use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\team\Team;
use common\entities\user\User;
use yii\helpers\ArrayHelper;

/**
 */
class ProfileForm extends ApiForm
{

    const SCENARIO_UPDATE = 'update';

    // Роль
    public $role;

    public $username;
    public $email;

    public $first_name;
    public $last_name;
    public $country_id;
    public $city_id;
    public $region_id;
    public $team_id;
    public $photo_id;
    public $description;

    public $organizer_name;
    public $organizer_legal_name;
    public $organizer_address;
    public $organizer_address_index;
    public $organizer_legal_address;
    public $organizer_legal_address_index;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        $rules = [
            ['first_name', 'trim'],
            ['first_name', 'string'],

            ['last_name', 'trim'],
            ['last_name', 'string'],


            ['email', 'trim'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass'     => User::class,
                'targetAttribute' => 'email',
                'message'         => 'Email уже существует',
                'except' => self::SCENARIO_UPDATE
            ],

            ['username', 'trim'],
            [
                'username',
                'unique',
                'targetClass'     => User::class,
                'targetAttribute' => 'username',
                'message'         => 'Username уже существует',
                'except' => self::SCENARIO_UPDATE
            ],

            ['country_id', 'trim'],
            [
                'country_id',
                'exist',
                'targetClass'     => Countries::class,
                'targetAttribute' => 'country_id',
                'message'         => 'Укажите страну из списка',
            ],

            ['city_id', 'trim'],
            [
                'city_id',
                'exist',
                'targetClass'     => Cities::class,
                'targetAttribute' => 'city_id',
                'message'         => 'Укажите город из списка',
            ],

            ['region_id', 'trim'],
            [
                'region_id',
                'exist',
                'targetClass'     => Regions::class,
                'targetAttribute' => 'region_id',
                'message'         => 'Укажите регион из списка',
            ],

            ['photo_id', 'trim'],
            ['photo_id', 'integer'],
            [
                'photo_id',
                'exist',
                'targetClass'     => Files::class,
                'targetAttribute' => 'id',
                'message'         => 'Загрузите изображение в photo/upload и укажите возвращенный id',
            ],

            ['description', 'trim'],
            ['description', 'string', 'max' => 340],


        ];

        if ($this->role == User::R_RACER)
        {
            $rules = ArrayHelper::merge($rules, [

                ['first_name', 'required', 'except' => self::SCENARIO_UPDATE],
                ['last_name', 'required', 'except' => self::SCENARIO_UPDATE],
                ['email', 'required', 'except' => self::SCENARIO_UPDATE],
                ['username', 'required', 'except' => self::SCENARIO_UPDATE],
                ['country_id', 'required', 'except' => self::SCENARIO_UPDATE],
                ['city_id', 'required', 'except' => self::SCENARIO_UPDATE],
                ['region_id', 'required', 'except' => self::SCENARIO_UPDATE],

                ['team_id', 'integer'],
                [
                    'team_id',
                    'exist',
                    'targetClass'     => Team::class,
                    'targetAttribute' => 'id',
                    'message'         => 'Укажите команду из списка',
                ],

            ]);
        }

        if ($this->role == User::R_GAPER)
        {
            $rules = ArrayHelper::merge($rules, [
                ['first_name', 'required', 'except' => self::SCENARIO_UPDATE],
                ['last_name', 'required', 'except' => self::SCENARIO_UPDATE],
                ['email', 'required', 'except' => self::SCENARIO_UPDATE],
                ['username', 'required', 'except' => self::SCENARIO_UPDATE],
                ['country_id', 'required', 'except' => self::SCENARIO_UPDATE],
                ['city_id', 'required', 'except' => self::SCENARIO_UPDATE],
                ['region_id', 'required', 'except' => self::SCENARIO_UPDATE],
            ]);
        }

        if ($this->role == User::R_ORGANIZER)
        {
            $rules = ArrayHelper::merge($rules, [

                ['first_name', 'required', 'except' => self::SCENARIO_UPDATE],

                ['username', 'required', 'except' => self::SCENARIO_UPDATE],

                ['organizer_name', 'trim'],
                ['organizer_name', 'required', 'except' => self::SCENARIO_UPDATE],
                ['organizer_name', 'string', 'max' => 255],

                ['organizer_legal_name', 'trim'],
                ['organizer_legal_name', 'string', 'max' => 255],

                ['organizer_address', 'trim'],
                ['organizer_address', 'string', 'max' => 255],

                ['organizer_address_index', 'trim'],
                ['organizer_address_index', 'string', 'max' => 255],

                ['organizer_legal_address', 'trim'],
                ['organizer_legal_address', 'string', 'max' => 255],

                ['organizer_legal_address_index', 'trim'],
                ['organizer_legal_address_index', 'string', 'max' => 255],

                ['photo_id', 'required', 'except' => self::SCENARIO_UPDATE],
                ['country_id', 'required', 'except' => self::SCENARIO_UPDATE],
                ['city_id', 'required', 'except' => self::SCENARIO_UPDATE],
                ['region_id', 'required', 'except' => self::SCENARIO_UPDATE],
            ]);
        }

        if ($this->role == User::R_JOURNALIST)
        {
            $rules = ArrayHelper::merge($rules, [
                ['first_name', 'required', 'except' => self::SCENARIO_UPDATE],
                ['last_name', 'required', 'except' => self::SCENARIO_UPDATE],
                ['organizer_name', 'string', 'max' => 255],
                ['email', 'required', 'except' => self::SCENARIO_UPDATE],
                ['country_id', 'required', 'except' => self::SCENARIO_UPDATE],
                ['city_id', 'required', 'except' => self::SCENARIO_UPDATE],
                ['region_id', 'required', 'except' => self::SCENARIO_UPDATE],
            ]);
        }

        return $rules;

    }
}


//
//racer
{
    //    "first_name" : "FirstName",
    //	"last_name" : "LastName",
    //	"email" : "email@email.kz",
    //	"username" : "username1",
    //	"country_id": 4,
    //	"city_id": 183,
    //
    //	"team_id": 1,
    //	"photo_id":"",
    //	"description":"racer racer racer"
}
//
//gaper
//{
//    "first_name" : "FirstName",
//	"last_name" : "LastName",
//	"email" : "email@email.kz",
//	"username" : "username1",
//	"country_id": 4,
//	"city_id": 183,
//
//	"photo_id":"",
//	"description":"racer racer racer"
//}
//
//organizer
//{
//    "last_name" : "LastName",
//	"organizer_name": "Название организации",
//	"organizer_name": "Название организации",
//	"organizer_name": "Название организации",
//	"email" : "email@email.kz",
//	"username" : "username1",
//	"country_id": 4,
//	"city_id": 183,
//
//	"organizer_legal_name": "Юредическое название организации",
//	"organizer_address": "адресс организации",
//	"organizer_legal_address": "Юредическое адресс организации",
//	"first_name" : "FirstName",
//	"photo_id":"",
//	"description":"racer racer racer"
//}
//
//journalist
//{
//    "first_name" : "FirstName",
//	"last_name" : "LastName",
//	"country_id": 4,
//	"city_id": 183,
//
//	"organizer_name": "Название организации",
//}

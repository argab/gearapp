<?php

namespace console\controllers;

use api\forms\news\NewsForm;
use common\base\Assert;
use common\dictionaries\EventType;
use common\dictionaries\NewsStatus;
use common\dictionaries\NewsType;
use common\dictionaries\Role;
use common\entities\event\Event;
use common\entities\file\Files;
use common\entities\news\News;
use common\entities\user\User;
use Faker\Factory;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

/**
 * Interactive console roles manager
 */
class FakeController extends Controller
{

    public function actionNews()
    {
        $users = User::find()->select('id')->all();
        $usersIds = ArrayHelper::getColumn($users, 'id');

        $photo = Files::find()->all();
        $photoIds = ArrayHelper::getColumn($photo, 'id');

        for ($i = 0; $i < 100; $i++)
        {

            $faker = Factory::create();

            $item = new News();

            $item->title = $faker->text(20);
            $item->description = $faker->text();

            $item->city_id = 183;
            $item->country_id = 4;
            $item->region_id = 1700503;

            $item->owner_id = array_rand($usersIds, 1);

            $item->photo_id = array_rand($photoIds, 1);

            $item->status = NewsStatus::keys()[array_rand(NewsStatus::keys())];

            $item->type = NewsType::keys()[array_rand(NewsType::keys())];


            $item->post_date = $faker->dateTimeBetween('-10 days', '-5 days')->format('Y-m-d H:i:s');
            $item->post_date_close = $faker->dateTimeBetween('+10 days', '+20 days')->format('Y-m-d H:i:s');

            $item->save();


        }

    }


    public function actionEvents()
    {
        $users = User::find()
            ->select([
                'id',
                'auth_assignments.item_name'
            ])
            ->leftJoin('auth_assignments', 'auth_assignments.user_id = user.id')
            ->andWhere(['item_name' => Role::R_ORGANIZER])
            ->all();

        $usersIds = ArrayHelper::getColumn($users, 'id');

        $photo = Files::find()->all();
        $photoIds = ArrayHelper::getColumn($photo, 'id');

        for ($i = 0; $i < 100; $i++)
        {

            $faker = Factory::create();

            $event = [
                "title"            => $faker->text(20),
                "description"      => $faker->text(200),
                "country_id"       => 4,
                "city_id"          => 183,
                "region_id"        => 1700503,
                "photo_id"         => array_rand($photoIds, 1),
                "type"             => EventType::CHAMPIONSHIP,
                "event_date_start" => date('Y-m-d H:i:s', strtotime('+7 days')),
                "event_date_end"   => date('Y-m-d H:i:s', strtotime('+7 days +60 minutes')),
                "latitude"         => $faker->latitude,
                "longitude"        => $faker->longitude,
                "is_hide"          => 0,
            ];

            $item = new Event($event);

            $item->owner_id = array_rand($usersIds, 1);

            $item->save();


        }

    }
}


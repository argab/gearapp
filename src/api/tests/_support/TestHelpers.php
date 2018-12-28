<?php
namespace api\tests;

use common\dictionaries\EventType;
use Faker\Factory;

trait TestHelpers
{
    public function fakeEvent($photo_id)
    {
        $faker = Factory::create();

        $event = [
            "title"            => $faker->text(20),
            "description"      => $faker->text(200),
            "country_id"       => 4,
            "city_id"          => 183,
            "region_id"        => 1700503,
            "photo_id"         => $photo_id,
            "type"             => EventType::CHAMPIONSHIP,
            "event_date_start" => date('Y-m-d H:i:s', strtotime('+7 days')),
            "event_date_end"   => date('Y-m-d H:i:s', strtotime('+7 days +60 minutes')),
            "latitude"         => $faker->latitude,
            "longitude"        => $faker->longitude,
            "is_hide"          => 0,
        ];

        return $event;
    }
}

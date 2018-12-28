<?php

return [
    'enablePrettyUrl'     => true,
    'enableStrictParsing' => true,
    'showScriptName'      => false,
    'cache'               => false,
    'rules'               => [
        ''                                        => 'site/index',

        //region Auth,Reset,User,Scheduler,Photo
        'POST,OPTIONS auth/signup'                => 'auth/auth/signup',
        'POST,OPTIONS auth/login'                 => 'auth/auth/login',
        'POST,OPTIONS auth/sms-code-confirm'      => 'auth/auth/sms-code-confirm',
        'POST,OPTIONS auth/sms-code-resend'       => 'auth/auth/sms-code-resend',
        'POST,OPTIONS auth/sms-code-set-password' => 'auth/auth/sms-code-set-password',

        'GET,OPTIONS auth/network' => 'auth/network-auth/auth',

        'POST,OPTIONS reset/email'         => 'auth/reset/by-email',
        'GET,OPTIONS reset/email-confirm'  => 'auth/reset/by-email-confirm',
        'POST,OPTIONS reset/phone'         => 'auth/reset/by-phone',
        'POST,OPTIONS reset/phone-confirm' => 'auth/reset/by-phone-confirm',

        "POST,OPTIONS user/info"         => 'user/info',
        "POST,OPTIONS user/set-phone"    => 'user/set-phone',
        "POST,OPTIONS user/set-role"     => 'user/set-role',
        "POST,OPTIONS user/search"       => 'user/search',
        "POST,OPTIONS user/set-password" => 'user/set-password',

        "GET,OPTIONS scheduler/online/check/<user_id:\d+>" => 'user/scheduler/check-user',
        "GET,OPTIONS scheduler/online/check"               => 'user/scheduler/check',
        "GET,OPTIONS scheduler/online/set"                 => 'user/scheduler/set-online',

        "POST,OPTIONS photo/upload"    => 'file/photo/upload',
        "GET,OPTIONS photo/<hash:\w+>" => 'file/photo/get',
        //endregion

        //region Geo
        "GET,OPTIONS geo/cities"    => 'geo/geo/cities',
        "GET,OPTIONS geo/countries" => 'geo/geo/countries',
        "GET,OPTIONS geo/regions"   => 'geo/geo/regions',
        "GET,OPTIONS geo/phone"     => 'geo/geo/phone',
        //endregion

        //region Role,Profile
        "GET,OPTIONS roles" => 'user/role/index',

        "GET,OPTIONS profile"  => 'user/profile/index',
        "POST,OPTIONS profile" => 'user/profile/create',
        "PUT,OPTIONS profile"  => 'user/profile/update',
        //endregion

        //region Team,TeamMembers

        "GET team/history/<team_id:\d+>" => 'team/team-history/by-id',

        "GET,OPTIONS team"     => 'user/team/index',
        "POST team"            => 'user/team/create',
        "PUT team/<id:\d+>"    => 'user/team/update',
        "DELETE team/<id:\d+>" => 'user/team/delete',

        "POST,OPTIONS team/search"  => 'user/team-search/search',
        "GET,OPTIONS team/<id:\d+>" => 'user/team/info',

        "GET,OPTIONS team-member/labels"                 => 'user/team-member/labels',
        "GET,OPTIONS team-member/<team_id:\d+>"          => 'user/team-member/index',
        "POST team-member/<team_id:\d+>/<user_id:\d+>"   => 'user/team-member/create',
        "DELETE team-member/<team_id:\d+>/<user_id:\d+>" => 'user/team-member/delete',
        "PUT team-member/<team_id:\d+>/<user_id:\d+>"    => 'user/team-member/update',
        //endregion


        //region Subscriptions - подписки пользовтеля
        'GET,OPTIONS subscriptions/user'               => 'user/subscriptions/user',
        'GET,OPTIONS subscriptions/user/<user_id:\d+>' => 'user/subscriptions/user-by-id',
        'GET,OPTIONS subscriptions/team'               => 'user/subscriptions/team',
        'GET,OPTIONS subscriptions/team/<user_id:\d+>' => 'user/subscriptions/team-by-id',

        'GET,OPTIONS subscriptions/to-user/<user_id:\d+>'     => 'user/subscriptions/subscribe-to-user',
        'GET,OPTIONS subscriptions/to-team/<team_id:\d+>'     => 'user/subscriptions/subscribe-to-team',
        'GET,OPTIONS subscriptions/u-from-user/<user_id:\d+>' => 'user/subscriptions/unsubscribe-from-user',
        'GET,OPTIONS subscriptions/u-from-team/<team_id:\d+>' => 'user/subscriptions/unsubscribe-from-team',
        //endregion


//        'GET,OPTIONS subscribe/users/<user_id:\d+>'       => 'user/subscribe/user-subscribers',
//        'GET,OPTIONS subscribe/teams/<team_id:\d+>'       => 'user/subscribe/team-subscribers',
//
//        'GET,OPTIONS subscribe/users/<user_id:\d+>/count' => 'user/subscribe/user-subscribers-count',
//        'GET,OPTIONS subscribe/teams/<team_id:\d+>/count' => 'user/subscribe/team-subscribers-count',
//
//
//        'GET,OPTIONS subscribe/block-user/<subscriber_id:\d+>'   => 'user/subscribe-block/block-subscriber',
//        'GET,OPTIONS subscribe/unblock-user/<subscriber_id:\d+>' => 'user/subscribe-block/unblock-subscriber',
//        "POST,OPTIONS subscribe" => 'subscribtion/subscribe/subscribe',




        'GET,OPTIONS get-info' => 'info/get-info',
        'GET get-info/groups' => 'info/get-groups',


        //region Car
        "GET,OPTIONS car/info/brands"     => 'car/car-info/brands',
        "GET,OPTIONS car/info/models"     => 'car/car-info/models',
        "GET,OPTIONS car/info/class"     => 'car/car-info/class',
        "GET,OPTIONS car/info/transmission"     => 'car/car-info/transmission',

        "GET,OPTIONS car/user/<id:\d+>" => 'car/car/index-by-user-id',
        "GET,OPTIONS car/<car_id:\d+>"  => 'car/car/car-by-id',
        "GET,OPTIONS car/"              => 'car/car/index',
        "POST car/"                     => 'car/car/create',
        "PUT car/<id:\d+>"              => 'car/car/update',
        "DELETE car/<id:\d+>"           => 'car/car/delete',

        "GET,OPTIONS car-team/<team_id:\d+>"              => 'car/car-team/index',
        "GET,OPTIONS car-team/<team_id:\d+>/<car_id:\d+>" => 'car/car-team/car-by-id',
        "POST car-team/<team_id:\d+>"                     => 'car/car-team/create',
        "PUT car-team/<team_id:\d+>/<car_id:\d+>"         => 'car/car-team/update',
        "DELETE car-team/<team_id:\d+>/<car_id:\d+>"      => 'car/car-team/delete',


        "GET car/photos/<car_id:\d+>" => 'car/car-photo/index',
        "POST car/photos/<car_id:\d+>" => 'car/car-photo/create',
        "DELETE car/photos/<car_id:\d+>/<photo_id:\d+>" => 'car/car-photo/delete',
        //endregion

        //region Garage
        "GET,OPTIONS garage/user/<id:\d+>" => 'car/garage-user/index',
        "POST,OPTIONS garage/user/<id:\d+>/<car_id:\d+>" => 'car/garage-user/create',
        "DELETE,OPTIONS garage/user/<id:\d+>/<car_id:\d+>" => 'car/garage-user/delete',

        "GET,OPTIONS garage/team/<id:\d+>" => 'car/garage-team/index',
        "POST,OPTIONS garage/team/<id:\d+>/<car_id:\d+>" => 'car/garage-team/create',
        "DELETE,OPTIONS garage/team/<id:\d+>/<car_id:\d+>" => 'car/garage-team/delete',
        //endregion


        //region News
        "GET,POST news/list"          => 'view-news-events/index',

        "GET,POST news/get"          => 'news/index',
        "GET,OPTIONS news/<id:\d+>" => 'news/by-id',
        "POST news/"                => 'news/create',
        "PUT news/<id:\d+>"         => 'news/update',
        "DELETE news/<id:\d+>"      => 'news/delete',

        "POST news/<id:\d+>/like"        => 'news/like',
        "POST news/<id:\d+>/dislike"     => 'news/dislike',
        "POST news/<id:\d+>/to-favorite" => 'news/to-favorite',
        "POST news/<id:\d+>/un-favorite" => 'news/un-favorite',

        "GET,OPTIONS news/dictionaries"          => 'news/dictionaries',
        //endregion


        //region Event
        "GET,POST event/get"          => 'event/index',
        "GET,OPTIONS event/<id:\d+>" => 'event/by-id',
        "POST event/"                => 'event/create',
        "PUT event/<id:\d+>"         => 'event/update',
        "DELETE event/<id:\d+>"      => 'event/delete',

        "POST event/<id:\d+>/like"        => 'event/like',
        "POST event/<id:\d+>/dislike"     => 'event/dislike',
        "POST event/<id:\d+>/to-favorite" => 'event/to-favorite',
        "POST event/<id:\d+>/un-favorite" => 'event/un-favorite',

        "GET,OPTIONS event/dictionaries"          => 'event/dictionaries',
        //endregion


    ],
];

<?php

namespace common\mediators;

use api\modules\subscribe\events\UserSubscribedEvent;

class SubscribeMediator
{
    public static function onUserSubscribed(UserSubscribedEvent $event)
    {
        $user =  $event->user;

        echo 'subscribe ok';
    }
}
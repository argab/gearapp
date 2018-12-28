<?php

namespace api\modules\subscribe;

use yii\base\Module;
use api\modules\subscribe\events\UserSubscribedEvent;

class SubscribeModule extends Module
{
    const EVENT_USER_SUBSCRIBED = 'userSubscribed';

    public $controllerNamespace = 'api\controllers\subscribtion';

    public function notifyThatUserSubscribed($model)
    {
        $this->trigger(SubscribeModule::EVENT_USER_SUBSCRIBED, new UserSubscribedEvent($model));
    }
}

<?php

namespace api\modules\subscribe\events;

use yii\base\Event;
use api\modules\subscribe\entity\Subscribe;

class UserSubscribedEvent extends Event
{
    public $model;

    public function __construct(Subscribe $model)
    {
        $this->model = $model;

        parent::__construct();
    }

}
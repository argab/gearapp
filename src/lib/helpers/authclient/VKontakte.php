<?php

namespace lib\helpers\authclient;

class VKontakte extends \yii\authclient\clients\VKontakte
{

    public function init()
    {
        $this->setStateStorage(CacheStateStorage::class);
        parent::init();
    }

}
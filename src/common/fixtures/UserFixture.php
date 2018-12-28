<?php

namespace common\fixtures;

use common\entities\user\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}
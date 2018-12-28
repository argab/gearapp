<?php

namespace common\dictionaries;

use Yii;

class UserStatus extends BaseDictionary
{
    const DELETED = 0;
    const BLOCKED = 1;
    const ACTIVE = 10;

    public static function all(): array
    {
        return [
            self::DELETED => Yii::t('app', 'Deleted'),
            self::BLOCKED => Yii::t('app', 'Blocked'),
            self::ACTIVE  => Yii::t('app', 'Active'),
        ];
    }

}

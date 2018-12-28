<?php
namespace common\dictionaries;

use Yii;

class Role extends BaseDictionary
{
    const R_ADMIN = 'admin';
    const R_GAPER = 'gaper';//зевака, зритель
    const R_JOURNALIST = 'journalist';
    const R_ORGANIZER = 'organizer';
    const R_RACER = 'racer';
    
    public static function all(): array
    {
        return [
            self::R_ADMIN      => Yii::t('app', 'Admin'),
            self::R_GAPER      => Yii::t('app', 'Viewer'),
            self::R_JOURNALIST => Yii::t('app', 'Journalist'),
            self::R_ORGANIZER  => Yii::t('app', 'Organizer'),
            self::R_RACER      => Yii::t('app', 'Driver'),
        ];
    }
    
}

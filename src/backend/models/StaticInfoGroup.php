<?php

namespace backend\models;

use Yii;
use yii\db\Query;

class StaticInfoGroup extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'static_info_groups';
    }

    public function rules()
    {
        return [
            ['name', 'unique'],
            [['name'], 'string'],
        ];
    }

    public static function getGroups()
    {
        return self::find()->asArray()->all();
    }
}

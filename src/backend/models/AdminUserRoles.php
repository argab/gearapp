<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use lib\services\RoleManager;

/**
 * @property integer $id                                           ;
 */
class AdminUserRoles extends ActiveRecord
{
    public static function tableName()
    {
        return 'auth_assignments';
    }

    public function attributeLabels()
    {
        return [
            'item_name' => 'Роль',
        ];
    }
}

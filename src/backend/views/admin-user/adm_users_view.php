<?php

use yii\helpers\Html;
use lib\grid\GridView;
use common\entities\user\User;
use backend\models\AdminUser;

$role = $model->getRoles()->one();

echo (new GridView($model))
    ->unsetFilds([
        'auth_key',
        'password_hash',
        'password',
        'password_reset_token'
    ])
    ->setRow('roles', @User::ROLES[$role->item_name] ?: $role->item_name)
    ->setRow('status', @AdminUser::STATUSES[$model->status] ?: $model->status)
    ->render();

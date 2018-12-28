<?php

use yii\helpers\Html;
use lib\grid\GridForm;
use backend\models\AdminUser;
use lib\services\RoleManager;

$rbac = new RoleManager;

/* @var $model \backend\models\AdminUser */

$grid = (new GridForm($model))
    ->setForm(['action' => '/admin-user/' . $model->scenario . ($model->id ? '?id=' . $model->id : null)])
    ->setInput('password', null, 'password');

if ($model->scenario === AdminUser::SCENARIO_UPDATE)
{
    $roles = $rbac->getRolesAll($model->id, true);

    $grid->setRadio('roles', $rbac->getRoleNames(), $roles);

    if ($model->id != Yii::$app->user->getId())

        $grid->toggleInputs([
            'username' => false,
            'phone' => false,
            'email' => false
        ]);
}

if (false == isAjax(false))

    $grid->setRow('submit_btn', Html::submitButton('Сохранить', ['class' => 'btn btn-info']));

echo $grid->render();

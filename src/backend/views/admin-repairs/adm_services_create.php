<?php

use yii\bootstrap\ActiveForm;
use backend\controllers\AdminRepairsController as RepairsHistory;

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
        'data-fn' => 'js-date-picker'
    ]
]) ?>

<?= $form->field($model, 'user_id')->textInput([
    'class' => 'form-control js-input-select',
    'data' => [
        'url' => '/admin-users/get-client'
    ]
]) ?>

<?= $form->field($model, 'services_date')->textInput([
    'id' => RepairsHistory::GETTER_REPAIRS . '_services_date',
    'class' => 'form-control js-date-picker'
]) ?>

<?= $form->field($model, 'model')->textInput() ?>

<?= $form->field($model, 'vin')->textInput() ?>

<?= $form->field($model, 'services_point_name')->textInput() ?>

<?= $form->field($model, 'services_point_address')->textInput() ?>

<?= $form->field($model, 'services_price')->input('number', ['min' => 0]) ?>

<?= $form->field($model, 'note')->textarea(['style' => ['resize' => 'vertical']]) ?>

<?= $form->field($model, 'doc_path')->fileInput() ?>

<?php ActiveForm::end() ?>

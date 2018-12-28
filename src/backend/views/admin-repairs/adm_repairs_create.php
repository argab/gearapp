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
        'url' => '/admin-user/get-client'
    ]
]) ?>

<?= $form->field($model, 'repairs_date')->textInput([
    'id' => RepairsHistory::GETTER_REPAIRS . '_repairs_date',
    'class' => 'form-control js-date-picker'
]) ?>

<?= $form->field($model, 'model')->textInput() ?>

<?= $form->field($model, 'vin')->textInput() ?>

<?= $form->field($model, 'point_name')->textInput() ?>

<?= $form->field($model, 'point_address')->textInput() ?>

<?= $form->field($model, 'repairs_price')->input('number', ['min' => 0]) ?>

<?= $form->field($model, 'doc_path')->fileInput() ?>

<?php ActiveForm::end() ?>

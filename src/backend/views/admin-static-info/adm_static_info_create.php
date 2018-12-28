<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Добавление информации';
$this->params['breadcrumbs'][] = ['label' => 'Статичная информация', 'url' => ['index']];

?>

<div class="col-md-6 col-sm-12  col-xs-12">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php
    include 'adm_static_info_fields.php';
    ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

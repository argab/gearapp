<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

$this->title = 'Добавление публикации';
$this->params['breadcrumbs'][] = ['label' => 'Публикации', 'url' => ['index']];

?>

<div class="col-md-6 col-sm-12  col-xs-12">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'category_id')->dropDownList($model->getCategoryList()); ?>
    <?= $form->field($model, 'meta_keys')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'meta_content')->textarea(['style' => ['min-height' => '100px']]) ?>
    <?= $form->field($model, 'short_desc')->textarea(['style' => ['min-height' => '100px']]) ?>
    <?= $form->field($model, 'content')->textarea(['style' => ['height' => '200px'], 'id' => 'f_desc_full', 'class' => 'js-tiny-mce form-control']) ?>
    <?= $form->field($model, 'status')->dropDownList(frontend\models\Articles::$statusName); ?>
    <?= $form->field($model, 'in_slider')->radioList(
        [
            0 => 'Нет',
            1 => 'Да'
        ],
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                return
                    '<div class="radio"><label>' . Html::radio($name, $checked, ['value' => $value]) . $label . '</label></div>';
            },
        ]
    );
    ?>
    <?= $form->field($model, 'slide_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->fileInput() ?>
    <?= $form->field($model, 'banner')->fileInput() ?>
    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

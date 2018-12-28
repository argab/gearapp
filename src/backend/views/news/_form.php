<?php

use kartik\widgets\DateTimePicker;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\entities\news\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description_short')->widget(\vova07\imperavi\Widget::class, [
        'settings' => [
            'lang'      => 'ru',
            'minHeight' => 200,
            'plugins'   => [
            ],
        ],
    ]) ?>

    <?= $form->field($model, 'description')->widget(\vova07\imperavi\Widget::class, [
        'settings' => [
            'lang'        => 'ru',
            'minHeight'   => 200,
            'imageUpload' => Url::to(['/file/image-upload']),
            'plugins'     => [
                'imagemanager',
            ],

        ],
    ]) ?>


    <?= $form->field($model, 'team_id')->widget(\kartik\select2\Select2::class, [
        'data'    => \common\entities\team\Team::map('id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Select team')]
    ]) ?>


    <?= $form->field($model, 'country_id')->widget(\kartik\select2\Select2::class, [
        'data'    => \common\entities\geo\Countries::map('country_id', 'title_ru'),
        'options' => ['placeholder' => Yii::t('app', 'Select country')]
    ]) ?>

    <?= $form->field($model, 'region_id')->widget(\kartik\select2\Select2::class, [
        'data'    => \common\entities\geo\Regions::map('region_id', 'title_ru'),
        'options' => ['placeholder' => Yii::t('app', 'Select region')]
    ]) ?>

    <?= $form->field($model, 'city_id')->widget(\kartik\select2\Select2::class, [
        'data'    => \common\entities\geo\Cities::map('city_id', 'title_ru'),
        'options' => ['placeholder' => Yii::t('app', 'Select city')]
    ]) ?>


    <?= $form->field($model, 'photo_id')->widget(FileInput::class, [
        'name'          => 'file[]',
        'options'       => [
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'uploadUrl'    => Url::to(['/file/image-upload']),
            'maxFileCount' => 1
        ]
    ]) ?>

    <?= $form->field($model, 'type')->widget(\kartik\select2\Select2::class, [
        'data'    => \common\dictionaries\NewsType::all(),
        'options' => ['placeholder' => Yii::t('app', 'Select type')]
    ]) ?>

    <?= $form->field($model, 'post_date')->widget(\kartik\datetime\DateTimePicker::class, [
        'options'       => ['placeholder' => Yii::t('app', 'Select time')],
        'pluginOptions' => [
            'autoclose' => true,
            'format'    => 'yyyy-mm-dd hh:ii:ss',
            'todayBtn'  => true
        ]
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

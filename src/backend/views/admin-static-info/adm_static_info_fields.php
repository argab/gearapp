<?php
use backend\models\StaticInfoGroup;
use yii\helpers\ArrayHelper;

$groups = StaticInfoGroup::getGroups();
?>

<?= $form->field($model, 'group_key', ['enableClientValidation' => false])->dropDownList(ArrayHelper::map($groups, 'key', 'name'), ['prompt' => '']); ?>
<?= $form->field($model, 'add_group')->textInput(['maxlength' => true]); ?>
<?= $form->field($model, 'key')->textInput(['maxlength' => true, 'placeholder' => 'Анг.буквы; Цифры; Нижнее подчеркивание; Дефис']) ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'value')->textarea(['class' => 'js-tiny-mce']) ?>
<?= $form->field($model, 'show')->radioList([
    '0' => 'Нет',
    '1' => 'Да',
]) ?>
<?= $form->field($model, 'priority')->textInput(['maxlength' => true]) ?>

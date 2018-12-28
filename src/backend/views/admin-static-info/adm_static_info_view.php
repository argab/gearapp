<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Статичная информация', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->group_name, 'url' => ['index?group_key=' . $model->group_key]];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="col-md-6 col-sm-12 col-xs-12">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'group_name',
            'key',
            'name',
            ['attribute' => 'value', 'format' => 'html'],
            ['attribute' => 'show', 'value' => $model->show ? 'Да' : 'Нет'],
            'priority',
        ],
    ]) ?>

</div>

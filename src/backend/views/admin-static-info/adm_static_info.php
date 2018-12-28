<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use backend\models\StaticInfoGroup;

Modal::begin([
    'id' =>'modal',
    'size' => 'modal-md',
]);
Modal::end();

$this->title = 'Статичная информация';

$groups = StaticInfoGroup::getGroups();

?>

<div class="col-md-12 col-sm-12 col-xs-12">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>&nbsp;&nbsp;
        <?= Html::a('Сохранить', null, [
            'onclick' => '$("form[name=save_priority]").submit()',
            'class' => 'btn btn-primary'
        ]) ?>&nbsp;&nbsp;
        <?php /*= Html::a('Группы', 'javascript:void()', [
            'onclick' => 'staticInfoGroups(); return false;',
            'class' => 'btn btn-info',
            'style' => ['min-width' => '85px'],
        ]) */?>
    </p>

    <?= Html::beginForm('/admin-static-info/save', 'post', [
        'name' => 'save_priority'
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $provider,
        'filterModel' => $model,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'priority',
                'value' => function($data){
                    return Html::input('text', 'priority[' . $data->id . ']', $data->priority, [
                        'class' => 'form-control',
                    ]);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'group_name',
                'value' => $model->group_name,
                'filter'=> Html::activeDropDownList($model, 'group_key', yii\helpers\ArrayHelper::map($groups,
                    'key',
                    'name'
                ),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'key',
            'name',
            [
                'attribute' => 'value',
                'format' => 'raw',
                'value' => function($data){
                    return nl2br(_truncate(strip_tags($data->value), '50', '...'));
                }
            ],
            [
                'attribute' => 'show',
                'value' => function($data){
                    return $data->show ? 'Да' : 'Нет' ;
                },
                'filter'=> Html::activeDropDownList($model, 'show', [0 => 'Нет', 1 => 'Да'],
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'tableOptions' => ['class' => 'table responsive table-striped table-bordered table-responsive table-hover'],
    ]); ?>

    <?= Html::endForm() ?>

</div>
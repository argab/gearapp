<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;

/*Modal::begin([
    'id' =>'modal',
    'size' => 'modal-lg',
]);
Modal::end();*/

$this->title = 'Публикации';

$catList = array(['key' => '', 'value' => null]);

if($cats = $model->getCategoryList())
{
    foreach ($cats as $k=>$v)
    {
        $catList[] = ['key' => $k, 'value' => $v];
    }
}

?>

<div class="col-md-12 col-sm-12 col-xs-12">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $provider,
        'filterModel' => $model,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'category_id',
                'value' => function ($model) use ($cats) {
                    return !empty($cats[$model->category_id]) ? $cats[$model->category_id] : '-НЕТ КАТЕГОРИИ-';
                },
                'filter'=> Html::activeDropDownList($model, 'category_id', yii\helpers\ArrayHelper::map($catList,
                    'key',
                    'value'
                ),
                    ['class'=>'form-control']
                )
            ],
            'user',
            'title',
            'slide_title',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return frontend\models\Articles::$statusName[$model->status];
                },
                'filter'=> Html::activeDropDownList($model, 'status', yii\helpers\ArrayHelper::map([
                    ['key' => null, 'value' => null],
                    ['key' => frontend\models\Articles::STATUS_BLOCKED, 'value' => frontend\models\Articles::$statusName[frontend\models\Articles::STATUS_BLOCKED]],
                    ['key' => frontend\models\Articles::STATUS_ACTIVE, 'value' => frontend\models\Articles::$statusName[frontend\models\Articles::STATUS_ACTIVE]],
                ],
                    'key',
                    'value'
                ),
                    ['class'=>'form-control']
                )
            ],
            [
                'attribute' => 'in_slider',
                'value' => function ($model) {
                    return $model->in_slider ? 'Да' : 'Нет';
                },
                'filter'=> Html::activeDropDownList($model, 'in_slider', yii\helpers\ArrayHelper::map([
                    ['key' => null, 'value' => null],
                    ['key' => 0, 'value' => 'Нет'],
                    ['key' => 1, 'value' => 'Да'],
                ],
                    'key',
                    'value'
                ),
                    ['class'=>'form-control']
                )
            ],
            [
                'attribute' => 'image_thumb',
                'value'=> function($data)
                {
                    return  Html::img(frontend\models\Articles::FILEPATH . $data->image_thumb, ['style' => ['max-width' => '80px']]);
                },
                'format' => 'raw'
            ],
            'short_desc',
            [
                'attribute' => 'file',
                'value'=> function($data)
                {
                    return  Html::a(frontend\models\Articles::FILEPATH . $data->file, $data->file, ['target' => '_blank']);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'banner',
                'value'=> function($data)
                {
                    return  $data->banner ? Html::img(frontend\models\Articles::FILEPATH . $data->banner, ['style' => ['max-width' => '150px']]) : null;
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'created_at',
                'format' => 'html',
                'filter' => \yii\jui\DatePicker::widget([
                    'model'=>$model,
                    'attribute'=>'created_at',
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options'    => [
                        'class' => 'form-control'
                    ],
                ]),
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'html',
                'filter' => \yii\jui\DatePicker::widget([
                    'model'=>$model,
                    'attribute'=>'updated_at',
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options'    => [
                        'class' => 'form-control'
                    ],
                ]),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'tableOptions' => ['class' => 'table responsive table-striped table-bordered table-responsive table-hover'],
    ]); ?>

</div>
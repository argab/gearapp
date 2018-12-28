<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\entities\news\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create News'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            
            //            'id',
            'title',
            'owner.phone',
            //            'team_id',
            //            'country_id',
            //            'city_id',
            //'region_id',
            //'description_short',
            'description:ntext',
            //'photo_id',
            [
                'attribute' => 'status',
                'filter'    => $searchModel->statusList(),
                'value'     => function(\common\entities\news\News $model){
                    return \common\dictionaries\NewsStatus::label($model->status);
                },
                'format'    => 'raw',
            ],
            [
                'attribute' => 'type',
                'filter'    => $searchModel->typeList(),
                'value'     => function(\common\entities\news\News $model){
                    return \common\dictionaries\NewsType::label($model->type);
                },
                'format'    => 'raw',
            ],
            //            [
            //                'attribute' => 'post_date',
            //                'filter' => DatePicker::widget([
            //                    'model' => $searchModel,
            //                    'attribute' => 'post_date_from',
            //                    'attribute2' => 'post_date_to',
            //                    'type' => DatePicker::TYPE_RANGE,
            //                    'separator' => '-',
            //                    'pluginOptions' => [
            //                        'todayHighlight' => true,
            //                        'autoclose'=>true,
            //                        'format' => 'yyyy-mm-dd',
            //                    ],
            //                ]),
            //                'format' => 'datetime',
            //            ],
            ['attribute' => 'post_date', 'format' => ['date', 'Y-M-d H:i:s'],],
            ['attribute' => 'post_date_close', 'format' => ['date', 'Y-M-d H:i:s'],],
            //'views',
            //'likes',
            //'shares',
            [
                'attribute' => 'created_at',
                'format'    => ['date', 'Y-M-d H:i:s'],
            ],
            //'updated_at',
            
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view} {moderate} {delete}',
                'buttons'  => [
                    'moderate' => function($url, $model, $key){
                        if($model->canModerate()){
                            return Html::a('moderate', ['moderate', 'id' => $model->id]);
                        }
                        return null;
                    }
                ]
            ],
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\entities\news\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->

    <p>
<!--        --><?//= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if($model->canModerate()){ ?>
            <?= Html::a(Yii::t('app', 'Moderate'), ['moderate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php } ?>


<!--        --><?//= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
//            'class' => 'btn btn-danger',
//            'data'  => [
//                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
//                'method'  => 'post',
//            ],
//        ]) ?>
    </p>

    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            'title',
            'owner.phone',
            'team_id',
            'country.title_ru',
            'city.title_ru',
            'region.title_ru',
            'description:ntext',
            [
                'attribute' => 'photo_id',
                'value'     => function(\common\entities\news\News $model){
                    return Html::img(\lib\services\file\FileService::getUrl($model->photo));
                },
                'format'    => 'raw',
            ],
            [
                'attribute' => 'status',
                'value'     => function(\common\entities\news\News $model){
                    return \common\dictionaries\NewsStatus::label($model->status);
                },
                'format'    => 'raw',
            ],
            [
                'attribute' => 'type',
                'value'     => function(\common\entities\news\News $model){
                    return \common\dictionaries\NewsType::label($model->type);
                },
                'format'    => 'raw',
            ],
            'post_date',
            'views',
            'likes',
            'shares',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

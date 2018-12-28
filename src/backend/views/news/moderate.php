<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\entities\news\News */

$this->title = Yii::t('app', 'Moderate News: ' . $model->title, [
    'nameAttribute' => '' . $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Moderate');
?>
<div class="news-moderate">


    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            'title',
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
            'created_at',
        ],
    ]) ?>

    <?php if ($model->canModerate()): ?>
        <?= Html::a('Reject', ['moderate', 'id' => $model->id, 'action' => 'reject'], ['class' => 'btn btn-danger', 'data-method' => 'post', 'data-confirm' => 'Уверены?']) ?>
        <?= Html::a('Public', ['moderate', 'id' => $model->id, 'action' => 'public'], ['class' => 'btn btn-success', 'data-method' => 'post', 'data-confirm' => 'Уверены?']) ?>
    <?php endif; ?>
    
    
    

</div>

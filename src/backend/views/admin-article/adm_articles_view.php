<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Публикации', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category, 'url' => ['index?Article[category_id]=' . $model->category_id]];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="col-md-6 col-sm-12 col-xs-12">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить данную статью?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'user',
            [
                'attribute' => 'status',
                'value' => frontend\models\Articles::$statusName[$model->status]
            ],
            [
                'attribute' => 'in_slider',
                'value' => $model->in_slider ? 'Да' : 'Нет'
            ],
            'slide_title',
            'category',
            'meta_keys',
            'meta_content',
            'short_desc',
            [
                'attribute' => 'content',
                'format' => 'raw'
            ],
            [
                'attribute' => 'image',
                'value'=> $model->image
                    ? frontend\models\Articles::FILEPATH . $model->image  . '<br>' . Html::img(frontend\models\Articles::FILEPATH . $model->image, ['style' => ['max-width' => '100%']]) : null,
                'format' => 'raw'
            ],
            [
                'attribute' => 'banner',
                'value'=> $model->banner
                    ? frontend\models\Articles::FILEPATH . $model->banner  . '<br>' . Html::img(frontend\models\Articles::FILEPATH . $model->banner, ['style' => ['max-width' => '100%']]) : null,
                'format' => 'raw'
            ],
            [
                'attribute' => 'file',
                'value'=> $model->file
                    ? Html::a(frontend\models\Articles::FILEPATH . $model->file, frontend\models\Articles::FILEPATH . $model->file, ['target' => '_blank']) : null,
                'format' => 'raw'
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

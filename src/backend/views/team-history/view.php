<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\entities\team\TeamHistory */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Team Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'team_id',
            'title',
            'description:ntext',
            'photo_id',
            'event_date',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

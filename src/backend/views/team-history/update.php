<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\entities\team\TeamHistory */

$this->title = 'Update Team History: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Team Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="team-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

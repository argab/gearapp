<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\entities\team\TeamHistory */

$this->title = 'Create Team History';
$this->params['breadcrumbs'][] = ['label' => 'Team Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

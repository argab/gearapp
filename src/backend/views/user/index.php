<?php

use backend\widgets\grid\RoleColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\entities\user\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <!--    <h1>--><? //= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            'username',
            'phone',
            'email:email',
            [
                'attribute' => 'sms_confirm',
                'filter'    => [1 => 'Yes', 0 => 'No'],
                'value'     => function(\common\entities\user\User $user){
                    return $user->sms_confirm ? 'Yes' : 'No';
                },
                'format'    => 'raw'
            ],
            [
                'attribute' => 'status',
                'filter'    => $searchModel->statusList(),
                'value'     => function(\common\entities\user\User $user){
                    return \common\dictionaries\UserStatus::get($user->status);
                },
                'format'    => 'raw'
            ],
            [
                'attribute' => 'role',
                'filter'    => $searchModel->rolesList(),
                'class'     => RoleColumn::class,
            ],
            'created_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

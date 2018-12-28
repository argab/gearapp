<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\user\User;
use backend\models\AdminUser;

$this->title = 'Пользователи';

?>

<div class="col-md-12 col-sm-12 col-xs-12">

    <?= Html::button('Добавить пользователя', [
        'class' => 'btn btn-success',
        'data' => [
            'href' => '/admin-user/create',
            'modal' => 'js-modal footer-controls',
            'modal-id' => 'modal-md',
            'modal-title' => 'Добавление пользователя',
        ]
    ]) ?>

    <br><br>

    <?= GridView::widget([
        'dataProvider' => $provider,
        'filterModel' => $model,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'class'      => 'yii\grid\ActionColumn',
                'template'   =>
                    Html::tag('span', '{update}', [
                        'data' => [
                            'modal'       => 'js-modal footer-controls',
                            'modal-id'    => 'modal-md',
                            'modal-title' => 'Редактирование пользователя',
                        ]
                    ])
                    . Html::tag('span', '&nbsp;&nbsp;{view}', [
                        'data' => [
                            'modal'       => 'js-modal footer-controls',
                            'modal-id'    => 'modal-md',
                            'modal-title' => 'Профиль пользователя',
                        ]
                    ])
            ],
            'username',
            'email:email',
            'phone',
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    return AdminUser::STATUSES[$data->status];
                },
                'filter'=> Html::activeDropDownList($model, 'status', AdminUser::STATUSES, [
                    'class'=>'form-control',
                    'prompt' => ''
                ])
            ],
            [
                'class'      => 'yii\grid\ActionColumn',
                'template'   => '{delete}'
            ],
        ],
        'tableOptions' => ['class' => 'table responsive table-striped table-bordered table-responsive table-hover'],
    ]); ?>

</div>
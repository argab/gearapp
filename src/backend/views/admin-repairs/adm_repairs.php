<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\controllers\AdminRepairsController as RepairsHistory;

$this->title = 'История ремонта авто';

$getter = Yii::$app->controller->getter();

?>

<div class="col-md-12 col-sm-12 col-xs-12">

    <?= Html::beginForm('/admin-repairs/update-all', 'post', ['enctype' => 'multipart/form-data']) ?>

    <?= Html::submitButton('Сохранить изменеия', ['class' => 'btn btn-info']) ?>
    <?= Html::button('Добавить запись', [
        'class' => 'btn btn-success',
        'data' => [
            'modal' => 'js-modal-md footer-controls',
            'modal-id' => 'modal-md',
            'href' => '/admin-repairs/create',
            'modal-title' => 'Добавление истории ремонта авто',
            'fn' => 'js-date-picker'
        ],
    ]) ?>

    <br><br>

    <?= GridView::widget([
        'dataProvider' => $provider,
        'filterModel' => $model,
        'columns' => [
            'id',
            [
                'class'      => 'yii\grid\ActionColumn',
                'template'   => '<div style="margin-bottom: 10px" onclick="toggleTableCellInputs(event, $(this))">{update}</div> {delete}',
                'urlCreator' => function($action, $model, $key, $index)
                {
                    return '/' . rtrim(Yii::$app->request->pathInfo, '/')
                        . '/'
                        . Yii::$app->controller->getter()
                        . '-'
                        . $action
                        . '?id='
                        . $key;
                }
            ],
            [
                'attribute' => 'user',
                'value'=> function($data) use ($getter)
                {
                    return  Html::a($data->user, '/admin-users/view?id=' . $data->user_id, ['target' => '_blank']);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'repairs_date',
                'format' => 'raw',
                'value' => function($data) use ($getter) {
                    return Html::tag('div',
                        Html::textInput(
                            $getter . '[' . $data->id . '][repairs_date]',
                            $data->repairs_date,
                            [
                                'class'    => 'form-control js-date-picker',
                                'disabled' => true
                            ]
                        ),
                        [
                            'class' => 'hidden'
                        ])
                    . Html::tag('span', date('d.m.Y', strtotime($data->repairs_date)));
                },
                'filter' => \yii\jui\DatePicker::widget([
                    'model'=>$model,
                    'attribute'=>'repairs_date',
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options'    => [
                        'class' => 'form-control'
                    ],
                ]),
            ],
            [
                'attribute' => 'model',
                'format' => 'raw',
                'value' => function($data) use ($getter) {
                    return Html::tag('div',
                            Html::textInput(
                                $getter . '[' . $data->id . '][model]',
                                $data->model,
                                [
                                    'class'    => 'form-control',
                                    'disabled' => true
                                ]
                            ),
                            [
                                'class' => 'hidden'
                            ])
                        . Html::tag('span', $data->model);
                },
            ],
            [
                'attribute' => 'vin',
                'format' => 'raw',
                'value' => function($data) use ($getter) {
                    return Html::tag('div',
                            Html::textInput(
                                $getter . '[' . $data->id . '][vin]',
                                $data->vin,
                                [
                                    'class'    => 'form-control',
                                    'disabled' => true
                                ]
                            ),
                            [
                                'class' => 'hidden'
                            ])
                        . Html::tag('span', $data->vin);
                },
            ],
            [
                'attribute' => 'works_names',
                'value' => function($data){
                    return Html::a((trim($data->works_names) !== ''
                            ? $data->works_names
                            : '<span style="color:orange">[Добавить]</span>'),
                        '/admin-repairs/' . RepairsHistory::GETTER_REPAIRS_WORKS . '?repairs_id=' . $data->id, [
                        'onclick' => 'return false',
                        'data' => [
                            'modal' => 'js-modal-xl footer-controls',
                            'modal-id' => 'modal-xl',
                            'modal-title' => $data->user
                                . ': работы по ремонту авто '
                                . ($data->model ?: null)
                                . ' от '
                                . date('d.m.Y', strtotime($data->repairs_date)),
                            'fn' => 'js-tiny-mce',
                        ]
                    ]);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'point_name',
                'format' => 'raw',
                'value' => function($data) use ($getter) {
                    return Html::tag('div',
                            Html::textInput(
                                $getter . '[' . $data->id . '][point_name]',
                                $data->point_name,
                                [
                                    'class'    => 'form-control',
                                    'disabled' => true
                                ]
                            ),
                            [
                                'class' => 'hidden'
                            ])
                        . Html::tag('span', $data->point_name);
                },
            ],
            [
                'attribute' => 'point_address',
                'format' => 'raw',
                'value' => function($data) use ($getter) {
                    return Html::tag('div',
                            Html::textInput(
                                $getter . '[' . $data->id . '][point_address]',
                                $data->point_address,
                                [
                                    'class'    => 'form-control',
                                    'disabled' => true
                                ]
                            ),
                            [
                                'class' => 'hidden'
                            ])
                        . Html::tag('span', $data->point_address);
                },
            ],
            [
                'attribute' => 'repairs_price',
                'format' => 'raw',
                'value' => function($data) use ($getter) {
                    return Html::tag('div',
                            Html::textInput(
                                $getter . '[' . $data->id . '][repairs_price]',
                                $data->repairs_price,
                                [
                                    'class'    => 'form-control',
                                    'disabled' => true
                                ]
                            ),
                            [
                                'class' => 'hidden'
                            ])
                        . Html::tag('span', $data->repairs_price);
                },
            ],
            [
                'attribute' => 'point_address',
                'format' => 'raw',
                'value' => function($data) use ($getter) {
                    return Html::tag('div',
                            Html::textInput(
                                $getter . '[' . $data->id . '][point_address]',
                                $data->point_address,
                                [
                                    'class'    => 'form-control',
                                    'disabled' => true
                                ]
                            ),
                            [
                                'class' => 'hidden'
                            ])
                        . Html::tag('span', $data->point_address);
                },
            ],
            [
                'attribute' => 'doc_path',
                'format' => 'raw',
                'value' => function($data) use ($getter) {

                    return Html::tag('div',
                            Html::fileInput($getter . '[' . $data->id . '][doc_path]', null, [
                                'class'    => 'form-control',
                                'disabled' => true
                            ]),
                            [
                                'class' => 'hidden'
                            ])
                        . Html::tag('span', Html::a($data->doc_path, $data::DOC_PATH . $data->doc_path, ['target' => '_blank']));
                },
            ],
        ],
        'tableOptions' => ['class' => 'table responsive table-striped table-bordered table-responsive table-hover'],
    ]) ?>

    <?= Html::endForm() ?>

</div>

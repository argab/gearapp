<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

$this->title = 'История ТО';

$getter = Yii::$app->controller->getter();

?>

<?= Html::beginForm('/admin-repairs/update-all-' . $getter, 'post', [
    'enctype' => 'multipart/form-data'
]) ?>

<?= Html::submitButton('Сохранить изменения', [
    'class' => 'btn btn-info'
]) ?>
<?= Html::button('Добавить запись', [
    'class' => 'btn btn-success',
    'style' => ['margin-left' => '5px'],
    'data' => [
        'modal' => 'js-modal-md footer-controls',
        'modal-id' => 'modal-md',
        'href' => '/admin-repairs/create-' . $getter,
        'modal-title' => 'Добавление истории ТО',
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
            'template'   => '<div class="adm-services-edit" onclick="toggleTableCellInputs(event, $(this))">{update}</div> {delete}',
            'urlCreator' => function($action, $model, $key, $index) use ($getter)
            {
                return $action . '-' . $getter . '?id=' . $key;
            }
        ],
        [
            'attribute' => 'user',
            'value'=> function($data)
            {
                return  Html::a($data->user, '/admin-users/view?id=' . $data->user_id, ['target' => '_blank']);
            },
            'format' => 'raw'
        ],
        [
            'attribute' => 'services_date',
            'format' => 'raw',
            'value' => function($data) use ($getter)
            {
                return Html::tag('div',
                        Html::input(
                            'input',
                            $getter . '[' . $data->id . '][services_date]',
                            $data->services_date,
                            [
                                'class'    => 'form-control js-date-picker',
                                'disabled' => true
                            ]
                        ),
                        [
                            'class' => 'hidden'
                        ])
                    . Html::tag('span', date('d.m.Y', strtotime($data->services_date)));
            },
            'filter' => \yii\jui\DatePicker::widget([
                'model'=>$model,
                'attribute'=>'services_date',
                'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'options'    => [
                    'class' => 'form-control'
                ],
            ]),
        ],
        [
            'attribute'     => 'model',
            'format' => 'raw',
            'value'         => function($data) use ($getter)
            {
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
            }
        ],
        [
            'attribute'     => 'vin',
            'format' => 'raw',
            'value'         => function($data) use ($getter)
            {
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
                    . Html::tag('span', mb_strtoupper($data->vin, 'UTF-8'));
            }
        ],
        [
            'attribute'     => 'services_point_name',
            'format' => 'raw',
            'value'         => function($data) use ($getter)
            {
                return Html::tag('div',
                        Html::textInput(
                            $getter . '[' . $data->id . '][services_point_name]',
                            $data->services_point_name,
                            [
                                'class'    => 'form-control',
                                'disabled' => true
                            ]
                        ),
                        [
                            'class' => 'hidden'
                        ])
                    . Html::tag('span', $data->services_point_name);
            }
        ],
        [
            'attribute'     => 'note',
            'format'        => 'raw',
            'value'         => function($data) use ($getter)
            {
                return Html::tag('div',
                        Html::textarea(
                            $getter . '[' . $data->id . '][note]',
                            $data->note,
                            [
                                'class' => 'form-control',
                                'disabled' => true,
                                'style' => [
                                    'resize' => 'vertical',
                                ]
                            ]
                        ),
                        [
                            'class' => 'hidden',
                        ])
                    . Html::tag('span', nl2br($data->note));
            }
        ],
        [
            'attribute'     => 'services_point_address',
            'format' => 'raw',
            'value'         => function($data) use ($getter)
            {
                return Html::tag('div',
                        Html::textInput(
                            $getter . '[' . $data->id . '][services_point_address]',
                            $data->services_point_address,
                            [
                                'class'    => 'form-control',
                                'disabled' => true
                            ]
                        ),
                        [
                            'class' => 'hidden'
                        ])
                    . Html::tag('span', $data->services_point_address);
            }
        ],
        [
            'attribute'     => 'services_price',
            'format' => 'raw',
            'value'         => function($data) use ($getter)
            {
                return Html::tag('div',
                        Html::textInput(
                            $getter . '[' . $data->id . '][services_price]',
                            $data->services_price,
                            [
                                'class'    => 'form-control',
                                'disabled' => true
                            ]
                        ),
                        [
                            'class' => 'hidden'
                        ])
                    . Html::tag('span', $data->services_price);
            }
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

<?php
Html::endForm()
?>

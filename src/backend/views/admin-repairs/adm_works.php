<?php

use yii\helpers\Html;
use yii\grid\GridView;

$worksItems = get_map($model->getWorksItemsList(), 'id', 'name');

$getter = Yii::$app->controller->getter();

$opt = ['class' => 'form-control'];

$addForm =
    Html::tag('td')
    . Html::tag('td', Html::activeDropDownList($model, 'works_id', $worksItems, $opt))
    . Html::tag('td', Html::activeTextarea($model, 'note', $opt))
    . Html::tag('td', Html::activeDropDownList($model, 'rate', [
        0 => '',
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
    ], $opt))
    . Html::tag('td')
;

?>

<style>textarea{resize: vertical;}</style>

<div class="col-md-12 col-sm-12 col-xs-12">

    <?= Html::beginForm('/admin-repairs/update-all-' . $getter . '?repairs_id=' . $model::$repairs_id, 'post', [
        'id' => 'ajax-form',
        'enctype' => 'multipart/form-data',
        'data-add-form' => str_replace(["\r", "\n", "\r\n"], ' ', $addForm)
    ]) ?>

    <?= Html::activeHiddenInput($model, 'repairs_history_id', ['value' => $model::$repairs_id]) ?>

    <?= GridView::widget([
        'id'           => 'grid-repairs-works',
        'dataProvider' => $provider,
        'columns'      => [
            'id',
            [
                'attribute'     => 'works_name',
                'format'        => 'raw',
                'value'         => function($data) use ($getter, $worksItems)
                {
                    return Html::tag('div',
                            Html::dropDownList(
                                $getter . '[' . $data->id . '][works_id]',
                                $data->works_id,
                                $worksItems,
                                [
                                    'class'    => 'form-control',
                                    'disabled' => true,
                                    'prompt'   => '',
                                ]
                            ),
                            ['class' => 'hidden'])
                        . Html::tag('span', $data->works_name);
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
                                nl2br(_truncate($data->note, 20)),
                                [
                                    'class' => 'form-control',
                                    'disabled' => true,
                                    'style' => [
                                        'resize' => 'none',
                                    ]
                                ]
                            ),
                            [
                                'class' => 'hidden',
                            ])
                        . Html::tag('span', $data->note);
                }
            ],
            [
                'attribute'     => 'rate',
                'format'        => 'raw',
                'value'         => function($data) use ($getter)
                {
                    return Html::tag('div',
                            Html::input(
                                'number',
                                $getter . '[' . $data->id . '][rate]',
                                $data->rate,
                                [
                                    'class'    => 'form-control',
                                    'max'      => 5,
                                    'min'      => 1,
                                    'step'     => 1,
                                    'disabled' => true
                                ]
                            ),
                            [
                                'class' => 'hidden'
                            ])
                        . Html::tag('span', $data->rate);
                }
            ],
            [
                'class'      => 'yii\grid\ActionColumn',
                'template'   => '<span class="js-toggle-inp" onclick="toggleTableCellInputs(event, $(this))">{update}</span>
                                <span class="rep-works-delete"> {delete}</span>',
                'urlCreator' => function($action, $model, $key, $index) use ($getter)
                {
                    return $action . '-' . $getter . '?id=' . $key . '&repairs_id=' . $model::$repairs_id;
                }
            ],
        ],
        'tableOptions' => ['class' => 'table responsive table-striped table-bordered table-responsive table-hover'],
    ]) ?>

    <?= Html::button('Добавить', ['id' => 'repairs-works-add', 'class' => 'btn btn-success']) ?>

    <?php
        Html::endForm()
    ?>

</div>

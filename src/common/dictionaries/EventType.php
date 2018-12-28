<?php


namespace common\dictionaries;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class EventType extends BaseDictionary
{
    const MEETING = 1;
    const CHAMPIONSHIP = 2;

    public static function all(): array
    {
        return [
            self::MEETING      => Yii::t('app', 'Meeting'),
            self::CHAMPIONSHIP => Yii::t('app', 'Championship'),
        ];
    }

    public static function label($item)
    {
        switch ($item)
        {
            case self::MEETING:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::all(), $item), [
            'class' => $class,
        ]);
    }


}

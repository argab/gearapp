<?php

namespace common\dictionaries;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class NewsType extends BaseDictionary
{
    const COMMON = 1;
    const ADVERTISING = 2;

    public static function all(): array
    {
        return [
            self::COMMON      => Yii::t('app', 'Advertising'),
            self::ADVERTISING => Yii::t('app', 'Common'),
        ];
    }

    public static function label($item)
    {
        switch ($item)
        {
            case self::ADVERTISING:
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

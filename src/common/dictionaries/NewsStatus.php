<?php

namespace common\dictionaries;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class NewsStatus extends BaseDictionary
{
    const IN_MODERATION = 1;
    const REJECTED = 2;
    const PUBLISHED = 3;
    const DRAFT = 4;

    public static function all(): array
    {
        return [
            self::IN_MODERATION => Yii::t('app', 'In moderation'),
            self::REJECTED      => Yii::t('app', 'Rejected'),
            self::PUBLISHED     => Yii::t('app', 'Published'),
            self::DRAFT     => Yii::t('app', 'Drafted'),
        ];
    }

    public static function label($item): string
    {
        switch ($item)
        {
            case self::IN_MODERATION:
                $class = 'label label-primary';
                break;
            case self::REJECTED:
                $class = 'label label-danger';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::all(), $item), [
            'class' => $class,
        ]);
    }
}

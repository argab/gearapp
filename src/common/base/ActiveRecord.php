<?php

namespace common\base;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class
ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = TimestampBehavior::class;
        return $behaviors;
    }

    public function objectFields()
    {
        return [];
    }

    public static function map($from, $to, $group = null)
    {
        return ArrayHelper::map(static::find()->all(), $from, $to, $group);
    }

    /**
     * @param $query - ActiveQuery
     * @param $from
     * @param $to
     * @param null $group
     * @return array
     */
    public static function queryMap(ActiveQuery $query, $from, $to, $group = null)
    {
        return ArrayHelper::map($query->all(), $from, $to, $group);
    }
}

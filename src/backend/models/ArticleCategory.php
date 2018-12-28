<?php

namespace backend\models;

use yii\helpers\ArrayHelper;

class ArticleCategory extends \yii\db\ActiveRecord
{
    const TABLE = 'article_categories';

    public static function tableName()
    {
        return self::TABLE;
    }

    public function rules()
    {
        return [
            ['name', 'unique'],
            [['priority'], 'integer'],
        ];
    }

    public static function getCategories()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }
}

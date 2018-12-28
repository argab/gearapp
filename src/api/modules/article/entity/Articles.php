<?php

namespace api\modules\article\entity;

use yii\base\Model;
use yii\db\Query;
use Yii;

class Articles extends Model
{
    const FILEPATH = '/uploads/articles/';

    const STATUS_ACTIVE = '1';
    const STATUS_BLOCKED = '0';

    public static $statusName = [
        self::STATUS_BLOCKED => 'Заблокировано',
        self::STATUS_ACTIVE => 'Активно',
    ];

    public static function getArticleSlider()
    {
        return query_all(
            'select * from `articles` where in_slider = 1 and status = :active order by `id` desc', [
                ':active' => self::STATUS_ACTIVE
        ]);
    }

    public static function getArticles() : Query
    {
        return (new Query())
            ->select(['a.*'])
            ->from('articles a')
            ->where('a.status=:active', [':active' => self::STATUS_ACTIVE])
            ->orderBy('a.created_at desc')
        ;
    }

    public static function getByID($id)
    {
        return query_one(
            'select * from `articles` where id = :id and status = :active order by `id` desc', [
                ':active' => self::STATUS_ACTIVE,
                ':id' => $id,
            ]
        );
    }

    public static function getCategories()
    {
        return query_all('select * from `article_categories` order by `priority`');
    }
}

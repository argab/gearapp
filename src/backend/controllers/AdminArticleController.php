<?php

namespace backend\controllers;

use Yii;
use lib\base\BaseController;
use backend\traits\TAdminController;

use backend\models\Article;

class AdminArticleController extends BaseController
{
    use TAdminController;

    const GETTER = 'article';

    protected $baseGetter = self::GETTER;

    protected $modelName = Article::class;

    protected $modelTable = [
        self::GETTER => Article::TABLE,
    ];

    protected $getters = [
        self::GETTER => 'findArticle',
    ];

    protected $views = [
        self::GETTER => [
            'index'  => 'adm_articles',
            'create' => 'adm_articles_create',
            'update' => 'adm_articles_update',
            'view'   => 'adm_articles_view',
        ],
    ];

    protected function _accessRules()
    {
        return [];
    }

    public function afterAction($action, $result)
    {
        return parent::afterAction($action, $result);
    }

}

<?php

namespace api\controllers\subscribe;

use Yii;
use yii\rest\Controller;
use common\entities\user\User;

use api\modules\article\ArticleModule;
use api\modules\article\entity\Articles;
use api\traits\TApiRestController;
use api\traits\TApiAuthGuest;

/* @property ArticleModule $module */
class ArticleController extends Controller
{
    use TApiRestController, TApiAuthGuest;


}

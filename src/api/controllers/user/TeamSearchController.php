<?php

namespace api\controllers\user;

use common\entities\team\Team;
use lib\helpers\Response;
use yii\rest\Controller;

class TeamSearchController extends Controller
{
    public function actionSearch()
    {
        $params = \Yii::$app->request->get();
        $items = Team::searchWithSerialize($params);

        return Response::responseItems($items);
    }
}

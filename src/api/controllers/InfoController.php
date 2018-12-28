<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use lib\helpers\Response;

use lib\base\StaticInfo;

class InfoController extends Controller
{
    public function actionGetInfo()
    {
        $param = Yii::$app->request->get('param');

        $group = Yii::$app->request->get('group');

        if ($get = StaticInfo::get($param, $group, true))

            return Response::success(['params' => $get]);

        return Response::responseError(['message' => 'params not found']);
    }

    public function actionGetGroups()
    {
        return Response::success(['groups' => StaticInfo::getGroups()]);
    }
}
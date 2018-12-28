<?php

namespace api\controllers\car;


use common\entities\car\CarBrand;
use common\entities\car\CarClass;
use common\entities\car\CarModel;
use common\entities\car\CarTransmission;
use lib\helpers\Response;
use yii\rest\Controller;

class CarInfoController extends Controller
{

    public function actionBrands()
    {
        $params = \Yii::$app->request->get();
        $items = CarBrand::searchWithSerialize($params, ['full']);

        return Response::responseItems($items);
    }

    public function actionModels()
    {
        $params = \Yii::$app->request->get();
        $items = CarModel::searchWithSerialize($params);

        return Response::responseItems($items);
    }

    public function actionClass()
    {
        $params = \Yii::$app->request->get();
        $items = CarClass::searchWithSerialize($params);

        return Response::responseItems($items);
    }

    public function actionTransmission()
    {
        $params = \Yii::$app->request->get();
        $items = CarTransmission::searchWithSerialize($params);

        return Response::responseItems($items);
    }

}
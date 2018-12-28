<?php

namespace api\controllers\geo;

use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\PhoneCode;
use common\entities\geo\Regions;
use lib\helpers\Response;
use yii\rest\Controller;

class GeoController extends Controller
{
    public function actionCities()
    {
        $params = \Yii::$app->request->get();
        $items = Cities::searchWithSerialize($params);

        return Response::responseItems($items);
    }

    public function actionRegions()
    {
        $params = \Yii::$app->request->get();
        $items = Regions::searchWithSerialize($params);

        return Response::responseItems($items);
    }

    public function actionCountries()
    {
        $params = \Yii::$app->request->get();
        $items = Countries::searchWithSerialize($params);

        return Response::responseItems($items);
    }

    public function actionPhone()
    {
        $params = \Yii::$app->request->get();
        $items = PhoneCode::searchWithSerialize($params);

        return Response::responseItems($items);
    }
}
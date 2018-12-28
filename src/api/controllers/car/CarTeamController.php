<?php

namespace api\controllers\car;

use api\forms\car\CarForm;
use api\traits\TApiProfileHttpAuth;
use api\traits\TApiRestController;
use common\entities\car\Car;
use common\entities\team\Team;
use lib\helpers\Response;
use yii\rest\Controller;

class CarTeamController extends Controller
{
    use TApiRestController, TApiProfileHttpAuth;

    public function actionIndex($team_id)
    {
        $item = Team::findByIdOrFail($team_id);
//        $item->failIfAuthUserNotOwner();

        return Response::responseItems(
            Car::serialize($item->cars, ['full'])
        );
    }

    public function actionCarById($team_id, $car_id)
    {
        $item = Car::findByIdAndTeamIdOrFail($car_id, $team_id);

        return Response::responseItem(
            Car::serializeItem($item, ['full'])
        );
    }


    public function actionCreate($team_id)
    {
        $form = CarForm::loadAndValidate();

        $team = Team::findByIdOrFail($team_id);
        $team->failIfAuthUserNotOwner();

        $item = Car::createWithoutSave($form->getAttributes());
        $item->team_id = $team->id;
        $item->saveOrFail();

        return Response::responseItem(
            Car::serializeItem($item, ['full']),
            201
        );
    }


    public function actionUpdate($team_id, $car_id)
    {
        $form = CarForm::loadAndValidate();

        $team = Team::findByIdOrFail($team_id);
        $team->failIfAuthUserNotOwner();

        $item = Car::findByIdAndTeamIdOrFail($car_id, $team_id);
        $item->load($form->getAttributes(), '');
        $item->saveOrFail();

        return Response::responseItem(
            Car::serializeItem($item, ['full']),
            201
        );
    }



    public function actionDelete($team_id, $car_id)
    {
        $item = Team::findByIdOrFail($team_id);
        $item->failIfAuthUserNotOwner();

        $item = Car::findByIdAndTeamIdOrFail($car_id,$team_id);
        $item->deleteOrFail();

        return Response::success([]);
    }

}
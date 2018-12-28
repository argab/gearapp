<?php

namespace api\controllers\car;

use api\exceptions\Http400Exception;
use api\traits\TApiProfileHttpAuth;
use api\traits\TApiRestController;
use common\entities\car\Car;
use common\entities\team\Team;
use lib\services\GarageService;
use yii\rest\Controller;

class GarageTeamController extends Controller
{

    use TApiRestController, TApiProfileHttpAuth;

    /**
     * @param $id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\NotFoundException
     */
    public function actionIndex($id)
    {
        $team = Team::findByIdOrFail($id);

        return $this->response->responseItems(
            Car::serialize($team->garage, ['full'])
        );
    }

    /**
     * @param $id
     * @param $car_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\Http400Exception
     * @throws \api\exceptions\NotFoundException
     */
    public function actionCreate($id, $car_id)
    {
        $team = Team::findByIdOrFail($id);
        $car = Car::findByIdOrFail($car_id);

        GarageService::createByTeam($team, $car);

        return $this->response->responseSuccess([], 201);
    }

    /**
     * @param $id
     * @param $car_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     * @throws \api\exceptions\NotFoundException
     */
    public function actionDelete($id, $car_id)
    {
        $team = Team::findByIdOrFail($id);
        $car = Car::findByIdOrFail($car_id);

        GarageService::removeByTeam($team, $car);

        return $this->response->success([]);
    }

}

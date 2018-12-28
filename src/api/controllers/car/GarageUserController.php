<?php

namespace api\controllers\car;

use api\exceptions\Http400Exception;
use api\traits\TApiProfileHttpAuth;
use api\traits\TApiRestController;
use common\entities\car\Car;
use common\entities\car\Garage;
use common\entities\user\User;
use lib\helpers\Response;
use lib\services\GarageService;
use yii\rest\Controller;

class GarageUserController extends Controller
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

        $user = User::findByIdOrFail($id);

        return $this->response->responseItems(
            Car::serialize($user->garage, ['full'])
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
        $user = User::findByIdOrFail($id);
        $car = Car::findByIdOrFail($car_id);

        GarageService::createByUser($user, $car);

        return $this->response->responseSuccess([], 201);
    }


    /**
     * @param $id
     * @param $car_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     * @throws \Exception
     * @throws \Throwable
     * @throws \api\exceptions\NotFoundException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, $car_id)
    {
        $user = User::findByIdOrFail($id);
        $car = Car::findByIdOrFail($car_id);

        GarageService::removeByUser($user, $car);

        return Response::success([]);
    }

}

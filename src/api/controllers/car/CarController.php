<?php

namespace api\controllers\car;

use api\forms\car\CarForm;
use api\traits\TApiProfileHttpAuth;
use api\traits\TApiRestController;
use common\entities\car\Car;
use common\entities\user\User;
use lib\helpers\Response;
use yii\rest\Controller;

class CarController extends Controller
{

    use TApiRestController, TApiProfileHttpAuth;

    public function actionIndex()
    {
        $user = User::authUser();

        return Response::responseItems(
            Car::serialize($user->cars, ['full'])
        );
    }

    public function actionCarById($car_id)
    {
        $item = Car::findByIdOrFail($car_id);

        return Response::responseItem(
            Car::serializeItem($item, ['full'])
        );
    }

    public function actionIndexByUserId($id)
    {
        $user = User::findByIdOrFail($id);

        return Response::responseItems(
            Car::serialize($user->cars, ['full'])
        );
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\Http400Exception
     */
    public function actionCreate()
    {
        $form = CarForm::loadAndValidate();
        $user = User::authUser();

        $item = Car::createWithoutSave($form->getAttributes());
        $item->user_id = $user->id;
        $item->saveOrFail();


        return Response::responseItem(
            Car::serializeItem($item, ['full']),
            201
        );
    }


    /**
     * @param $id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\Http400Exception
     * @throws \api\exceptions\NotFoundException
     */
    public function actionUpdate($id)
    {
        $form = CarForm::loadAndValidate();

        $item = Car::findByIdOrFail($id);
        $item->load($form->getAttributes(), '');
        $item->saveOrFail();


        return Response::responseItem(
            Car::serializeItem($item, ['full']),
            201
        );
    }


    /**
     * @param $id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \api\exceptions\Http400Exception
     * @throws \api\exceptions\NotFoundException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $item = Car::findByIdOrFail($id);
        $item->deleteOrFail();

        return Response::success([]);
    }

}
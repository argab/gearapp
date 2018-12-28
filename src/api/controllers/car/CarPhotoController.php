<?php

namespace api\controllers\car;

use api\forms\car\CarForm;
use api\forms\car\CarPhotoForm;
use api\traits\TApiProfileHttpAuth;
use api\traits\TApiRestController;
use common\entities\car\Car;
use common\entities\file\Files;
use common\entities\user\User;
use lib\helpers\Response;
use yii\rest\Controller;

class CarPhotoController extends Controller
{

    use TApiRestController, TApiProfileHttpAuth;

    public function actionIndex($car_id)
    {
        $car = Car::findByIdOrFail($car_id);

        return Response::responseItems(Files::serialize($car->photos, ['full']));
    }

    /**
     * @param $car_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\NotFoundException
     */
    public function actionCreate($car_id)
    {

        $form = CarPhotoForm::loadAndValidate();
        $car = Car::findByIdOrFail($car_id);

        $photos = Files::findByIdArr($form->photo_id);
        $car->linkPhotos($photos);

        return Response::responseItem(
            Car::serializeItem($car, ['full']),
            201
        );
    }

    /**
     * @param $car_id
     * @param $photo_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\NotFoundException
     */
    public function actionDelete($car_id, $photo_id)
    {
        $car = Car::findByIdOrFail($car_id);
        $photo = Files::findByIdOrFail($photo_id);

        $carPhotoIds = $car->getPhotosIds();

        if (in_array($photo_id, $carPhotoIds))
            $car->unlink('photos', $photo);

        return Response::success([]);
    }


}
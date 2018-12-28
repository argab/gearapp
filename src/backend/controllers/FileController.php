<?php

namespace backend\controllers;

use api\forms\file\PhotosForm;
use lib\services\file\FileService;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * NewsController implements the CRUD actions for News model.
 */
class FileController extends Controller
{
    public function verbs(): array
    {
        return [
            'image-upload' => ['POST'],
            'get'    => ['GET'],
        ];
    }


    /**
     * @throws \api\exceptions\Http400Exception
     */
    public function actionImageUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new PhotosForm();
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        $file = FileService::saveOrReturnExistFile($form->file);
        $url = FileService::getUrl($file);

        return [
            [
                'thumb' => $url,
                'url'   => $url,
                'id'    => $file->id,
                'title' => $file->name,
            ]
        ];

    }


    /**
     * @param $hash
     *
     * @throws Http400Exception
     * @throws ServerErrorHttpException
     */
    public function actionGet($hash)
    {
        if ( ! $file = FileService::checkIfFileExistByHash($hash))
            throw new Http400Exception('File not found');

        $response = \Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'image/jpeg');
        $response->format = \yii\web\Response::FORMAT_RAW;
        if ( ! is_resource($response->stream = fopen($file->path, 'r')))
        {
            throw new ServerErrorHttpException('file access failed: permission deny');
        }

        return $response->send();
    }
}

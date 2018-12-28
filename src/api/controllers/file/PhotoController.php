<?php

namespace api\controllers\file;

use api\exceptions\Http400Exception;
use api\forms\file\PhotosForm;
use lib\helpers\Response;
use lib\services\file\FileService;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class PhotoController extends Controller
{

    private $response;

    public function __construct($id,
        $module,
        \yii\web\Response $response,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->response = $response;
    }

    public function verbs(): array
    {
        return [
            'upload' => ['POST'],
            'get'    => ['GET'],
        ];
    }


    /**
     * @throws \api\exceptions\Http400Exception
     */
    public function actionUpload()
    {
        $form = new PhotosForm();
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        $file = FileService::saveOrReturnExistFile($form->file);

        return Response::success([
            'file' => FileService::serializeFile($file)
        ]);
    }



    public function actionGet($hash)
    {
        if (!$file = FileService::checkIfFileExistByHash($hash)){
            $path = $this->getEmptyImage();
            return $this->returnImage($path);
        }

        $path = \Yii::getAlias($file->path . $file->file_name);

        if(!file_exists($path))
            $path = $this->getEmptyImage();

        return $this->returnImage($path);
    }

    private function getEmptyImage()
    {
        return \Yii::getAlias('@api/web/no_photo.png');
    }

    private function returnImage($path)
    {
        $this->response->headers->set('Content-Type', 'image/jpeg');
        $this->response->format = \yii\web\Response::FORMAT_RAW;
        if (!is_resource($this->response->stream = fopen($path, 'r'))){
            throw new ServerErrorHttpException('file access failed: permission deny');
        }
        return $this->response->send();
    }


}

<?php

namespace lib\services\file;


use api\exceptions\Http400Exception;
use common\entities\file\Files;
use common\entities\user\User;
use yii\helpers\Url;
use yii\web\UploadedFile;

class FileService
{

    /**
     * @param UploadedFile $file
     *
     * @return array|Files|null|\yii\db\ActiveRecord
     * @throws Http400Exception
     */
    public static function saveOrReturnExistFile(UploadedFile $file)
    {
        $hash = FileService::getHashByFile($file->tempName);
        $existFile = FileService::checkIfFileExistByHash($hash);
        if ($existFile)
            return $existFile;

        return FileService::saveFile($file, $hash);
    }

    public static function getHashByFile($file)
    {
        return hash_file('md5', $file);
    }

    /**
     * @param $hash
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function checkIfFileExistByHash($hash)
    {
        $file = Files::findByHash($hash);
        if ( ! $file)
            return null;

        $path = self::getPathByFile($file);
        if ( ! file_exists($path))
            return null;

//        $file->path = $path;

        return $file;
    }

    public static function getPathByFile(Files $file)
    {
        return \Yii::getAlias($file->path . $file->file_name);
    }

    public static function serializeFile(Files $file)
    {
        $temp = [
            'id'  => $file->id,
            'hash'  => $file->hash,
            'name'  => $file->name,
            '_link' => Url::to(['/photo/' . $file->hash]),
//            '_link' => self::getUrl($file)
        ];

        return $temp;
    }

    public static function getUrl(Files $file)
    {
        $path = \Yii::getAlias('@staticSitePhoto');
        $filePath = str_replace('@photo/', '', $file->path );
        return $path.$filePath.$file->file_name;
    }


    public static function uploadPhoto($file)
    {

    }

    /**
     * @param UploadedFile $file
     * @param null $hash
     *
     * @return Files
     * @throws Http400Exception
     */
    public static function saveFile(UploadedFile $file, $hash = null): Files
    {
        if ( ! $hash)
            $hash = self::getHashByFile($file->tempName);

        $path = '@photo/' . date('Ymd') . '/';
        $pathAbs = \Yii::getAlias($path);

        if ( ! file_exists($pathAbs))
            mkdir($pathAbs, 0777, true);

        $ext = $file->getExtension();
        $name = $file->getBaseName();
        $pathAbs = \Yii::getAlias($path . $hash . '.' . $ext);


        if (!$file->saveAs($pathAbs, true))
            throw new Http400Exception('File save error');

        $data = [
	        'hash'      => $hash,
	        'name'      => $name . '.' . $ext,
	        'type'      => $file->type,
	        'size'      => $file->size,
	        'file_name' => $hash . '.' . $ext,
	        'path'      => $path,
        ];

        if($user = User::authUser())
        	$data['user_id'] = $user->id;

        $photo = new Files($data);

        if (!$photo->save())
            throw new Http400Exception('Photo save error ' . json_encode([$photo->getErrors(), $data]));


        return $photo;
    }

}

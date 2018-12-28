<?php

namespace common\traits;

use api\forms\PhotosForm;
use common\entities\file\Photos;
use yii\helpers\ArrayHelper;

trait PhotosTrait
{
    public function attachPhotos(PhotosForm $photos)
    {
        if (empty($photos->photos))
            return;

        foreach ($photos->photos as $photo_id)
        {
            Photos::createByModelAndId(get_class($this), $this->id, $photo_id);
        }
    }

    public function detachPhotos()
    {
        Photos::deleteByModelAndId(self::class, $this->id);
    }

    public function getPhotos()
    {
        return $this->hasMany(Photos::class, ['model_id' => 'id'])
            ->where([
                'photos.model'    => get_class($this),
                'photos.model_id' => $this->id
            ]);
    }

    public function getPhotosFile()
    {
        return array_map(function($item){
            return $item->file;
        }, $this->photos);
    }
}

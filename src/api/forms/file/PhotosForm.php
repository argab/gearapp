<?php

namespace api\forms\file;

use api\forms\ApiForm;
use yii\web\UploadedFile;

class PhotosForm extends ApiForm
{
    /**
     * @var UploadedFile[]
     */
    public $file;

    public function rules(): array
    {
        return [
            ['file', 'required'],
            ['file', 'file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxSize' => 1024 * 1024],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate())
        {
            $this->file = UploadedFile::getInstanceByName('file');

            return true;
        }

        return false;
    }
}

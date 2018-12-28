<?php

namespace api\forms;

use common\entities\file\Files;
use yii\base\Model;

class PhotoForm extends Model
{
    public $photo_id;

    public function rules(): array
    {
        return [
            [['photo_id'], 'exist', 'targetClass' => Files::class, 'targetAttribute' => ['photo_id' => 'id']],
        ];
    }


}

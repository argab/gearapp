<?php

namespace api\forms\car;

use api\forms\ApiForm;
use common\entities\file\Files;

class CarPhotoForm extends ApiForm
{

    public $photo_id;

    public function rules(): array
    {
        return [
            ['photo_id', 'each', 'rule' => ['exist', 'targetClass' => Files::class, 'targetAttribute' => 'id']],
        ];
    }


}
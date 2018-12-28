<?php

namespace api\forms;

use common\entities\file\Files;
use yii\base\Model;

class PhotosForm extends Model
{
    public $photos;

    public function rules(): array
    {
        return [
            ['photos', 'each', 'rule' => ['integer']],
            ['photos', 'each', 'rule' => ['exist', 'targetClass' => Files::class, 'targetAttribute' => 'id']]
        ];
    }


}

<?php

namespace api\forms\car;

use api\forms\ApiForm;
use common\entities\car\CarBrand;
use common\entities\car\CarClass;
use common\entities\car\CarModel;
use common\entities\car\CarTransmission;
use common\entities\file\Files;
use common\entities\user\User;

class CarForm extends ApiForm
{

    public $cb_id;
    public $cm_id;
    public $cc_id;
    public $ct_id;
    public $year;
    public $volume;
    public $horsepower;
    public $main_photo_id;
    public $information;
    public $equipment;

    public function rules(): array
    {
        return [
            [['cb_id', 'cm_id', 'cc_id', 'ct_id', 'main_photo_id'], 'integer'],
            ['year', 'integer', 'min' => 1800, 'max' => 2020],
            [['information', 'equipment'], 'string', 'max' => 500 ],
            [['information', 'equipment'], 'trim'],
            [['volume', 'horsepower'], 'number', 'numberPattern' => '/^\d+(.\d{1,2})?$/'],
            [['cb_id'], 'exist', 'targetClass' => CarBrand::class, 'targetAttribute' => ['cb_id' => 'id']],
            [['cc_id'], 'exist', 'targetClass' => CarClass::class, 'targetAttribute' => ['cc_id' => 'id']],
            [['cm_id'], 'exist', 'targetClass' => CarModel::class, 'targetAttribute' => ['cm_id' => 'id']],
            [['ct_id'], 'exist', 'targetClass' => CarTransmission::class, 'targetAttribute' => ['ct_id' => 'id']],
            [['main_photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['main_photo_id' => 'id']],
        ];
    }


}

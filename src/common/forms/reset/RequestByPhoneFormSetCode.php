<?php

namespace common\forms\reset;

use yii\base\Model;

/**
 * Password reset request form
 */
class RequestByPhoneFormSetCode extends Model
{
    public $code;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['code', 'trim'],
            ['code', 'integer'],
            ['code', 'required'],
        ];
    }


}

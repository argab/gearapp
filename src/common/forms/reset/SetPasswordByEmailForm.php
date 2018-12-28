<?php

namespace common\forms\reset;

use lib\services\auth\ResetPasswordByEmailService;
use yii\base\Model;

/**
 * Password reset form
 */
class SetPasswordByEmailForm extends Model
{
    public $password;
    /**
     * @var \common\entities\User
     */
    public $user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     *
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        $this->user = (new ResetPasswordByEmailService())->validateToken($token);
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }


}

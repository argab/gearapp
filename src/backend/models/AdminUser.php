<?php

namespace backend\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use lib\services\RoleManager;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\web\UploadedFile;

use common\entities\user\User;
use common\traits\TModel;
use lib\grid\IGridFormProvider;

/**
 * @property integer $id                                           ;
 */
class AdminUser extends ActiveRecord implements IGridFormProvider
{
    use TModel;

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

    const TABLE = 'user';

    const STATUSES = [
        User::STATUS_ACTIVE  => 'Активен',
        User::STATUS_BLOCKED => 'Заблокирован',
        User::STATUS_DELETED => 'Помечен на удаление',
    ];

    public $roles, $rules, $permissions;

    public function init()
    {
        $this->safeInputs = array_merge($this->safeInputs, [
            'sms_confirm',
            'auth_key',
            'password_hash',
            'password_reset_token'
        ]);
    }

    public function beforeValidate()
    {
        if ($pass = request_post('password'))

            $this->password_hash = User::generatePassword($pass);

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ( ! $this->isNewRecord && $this->id != Yii::$app->user->getId())

            unset($this->phone, $this->email, $this->username);

        return parent::beforeSave($insert);
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'email'    => 'E-mail',
            'phone'    => 'Телефон',
            'status'   => 'Статус',
            'password' => 'Пароль',
            'roles'    => 'Роль',
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'phone', 'password'], 'filter', 'filter' => 'trim'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email', 'on' => [static::SCENARIO_CREATE, static::SCENARIO_UPDATE]],
            [['email'], 'string', 'max' => 255],
            ['password', 'string', 'min' => 6],
            ['email', 'unique', 'message' => 'Выбранный E-Mail уже используется..', 'on' => [static::SCENARIO_CREATE]],
            ['phone', 'unique', 'message' => 'Выбранный Телефон уже используется..', 'on' => [static::SCENARIO_CREATE]],
            ['username', 'unique', 'message' => 'Выбранное имя уже используется..', 'on' => [static::SCENARIO_CREATE]],
            [['username', 'phone'], 'required', 'on' => [static::SCENARIO_CREATE]],
            ['phone', 'string', 'max' => 20],
	        [
		        'phone', 'unique',
		        'targetClass' => User::class,
		        'message'     => 'Нет пользователья с таким телефоном',
		        'on'      => [static::SCENARIO_CREATE, static::SCENARIO_UPDATE]
	        ],
            [
                [
                    'id',
                    'password_reset_token',
                    'password_hash',
                    'status',
                    'sms_confirm',
                    'auth_key'
                ],
                'safe'
            ],
        ];
    }

    public function getInputOptions(): array
    {
        return [
            'status' => self::STATUSES
        ];
    }

    public function findUser($id = 0)
    {
        $q = $this->find()->alias('u')->with('roles');

        return $id ? $q->andWhere(['u.id' => $id]) : $q;
    }

    public function getRoles()
    {
        return $this->hasMany(AdminUserRoles::class, ['user_id' => 'id']);
    }

    public function filter(Query & $query)
    {
        $query->andFilterWhere(['=', 'u.id', $this->id])
            ->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['like', 'u.phone', $this->phone])
            ->andFilterWhere(['=', 'u.status', $this->status]);
    }

}

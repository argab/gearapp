<?php

namespace common\entities\user;


use api\traits\TApiModel;
use common\entities\car\Car;
use common\entities\team\Team;
use lib\helpers\DateHelper;
use lib\helpers\UserHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 * @property integer $id
 * @property string $phone
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property bool $sms_confirm
 * @property bool $online
 * @property $last_online
 * @property $garage
 * @property $userSubscriptions
 * @property $teamSubscriptions
 */
class User extends ActiveRecord implements IdentityInterface {
	use TApiModel;

	const STATUS_DELETED = 0;
	const STATUS_BLOCKED = 1;
	const STATUS_ACTIVE = 10;

	const R_ADMIN = 'admin';
	const R_GAPER = 'gaper';//зевака, зритель
	const R_JOURNALIST = 'journalist';
	const R_ORGANIZER = 'organizer';
	const R_RACER = 'racer';

	const ROLES = [
		self::R_ADMIN      => 'Администратор',
		self::R_GAPER      => 'Зевака',
		self::R_JOURNALIST => 'Журналист',
		self::R_ORGANIZER  => 'Организатор',
		self::R_RACER      => 'Гонщик',
	];

	public static $user;

	/**
	 * @param string $phone
	 *
	 * @return User
	 */
	public static function create(string $phone): self {
		$user        = new self();
		$user->phone = $phone;
		$user->setPassword(Yii::$app->security->generateRandomKey(12));
		//		$user->generateSmsCode();
		$user->generateAuthKey();

		return $user;
	}

	public static function searchFields() {
		return [
			'username',
			'phone',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%user}}';
	}
    
    /**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			TimestampBehavior::className(),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['status', 'default', 'value' => self::STATUS_ACTIVE],
			['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
		];
	}


	/**
	 * {@inheritdoc}
	 */
	public static function findIdentity($id) {
		return static::find()
		             ->where(['id' => $id, 'status' => self::STATUS_ACTIVE])
		             ->with(
			             'profile',
			             'profile.city',
			             'profile.country',
			             'profile.photo'
		             )
		             ->limit(1)
		             ->one();
	}

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		Token::clearOldTokens();

		try {
			$user = Token::getUserByToken($token);

			return $user;
		} catch (\DomainException $e) {
			return false;
		}
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 *
	 * @return static|null
	 */
	public static function findByPhone($phone) {
		return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
	}

	public static function findByUsername($username) {
		return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 *
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token) {
		if ( ! static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne([
			'password_reset_token' => $token,
			'status'               => self::STATUS_ACTIVE,
		]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 *
	 * @return bool
	 */
	public static function isPasswordResetTokenValid($token) {
		if (empty($token)) {
			return false;
		}

		$timestamp = (int) substr($token, strrpos($token, '_') + 1);
		$expire    = Yii::$app->params['user.passwordResetTokenExpire'];

		return $timestamp + $expire >= time();
	}

	public static function generatePassword($password) {
		return Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId() {
		return $this->getPrimaryKey();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthKey() {
		return $this->auth_key;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateAuthKey($authKey) {
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 *
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password) {
		return Yii::$app->security->validatePassword($password, $this->password_hash);
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 *
	 * @throws \yii\base\Exception
	 */
	public function setPassword($password) {
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey() {
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken() {
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken() {
		$this->password_reset_token = null;
	}


	public function getToken() {
		return $this->hasMany(Token::class, ['user_id' => 'id']);
	}


	/**
	 * Устанавливет флаг
	 * @return bool
	 */
	public function setSmsConfirmTrue() {
		$this->sms_confirm = true;

		return $this->save();
	}


	/**
	 * Зарегестирированный пользователь
	 * @return null|IdentityInterface|static
	 */
	public static function authUser() {
		if ( ! self::$user) {
			self::$user = User::findIdentity(Yii::$app->user->id);
		}

		return self::$user;
	}

	//region Roles
	public function roleIs($roleName) {
		$roles = self::getRoles();
		if (empty($roles)) {
			return false;
		}

		return array_key_exists($roleName, $roles);
	}

	/**
	 * Это не связь
	 */
	public function getRoles(): array {
		$manager = Yii::$app->authManager;
		$roles   = $manager->getRolesByUser($this->id);

		return $roles;
	}

	/**
	 * Нет роли
	 * @return bool
	 */
	public function isRoleEmpty() {
		return empty($this->getRoles());
	}

	public function getFirstRoleName() {
		$roles = $this->getRoles();
		if (empty($roles)) {
			return null;
		}

		return array_keys($roles)[0];
	}
    
    public function hasRole($array)
    {
        $roles = array_keys($this->getRoles());
        foreach ($array as $item){
            if(in_array($item, $roles))
                return true;
        }
        
        return false;
    }

	public function getProfile() {
		return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
	}

	public function getTeams() {
		return $this->hasMany(Team::class, ['creator_id' => 'id']);
	}

	public function getCars() {
		return $this->hasMany(Car::class, ['user_id' => 'id']);
	}

	public function getGarage() {
		return $this->hasMany(Car::class, ['id' => 'car_id'])
		            ->viaTable('garage', ['user_id' => 'id']);
	}

    public function getSubscribers() {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable(UserSubscribe::tableName(), ['subscriber_id' => 'id']);
    }

    /*
     * К каким пользователям подписан юзер
     */
    public function getUserSubscriptions()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable(UserSubscribe::tableName(), ['subscriber_id' => 'id']);
    }

    /*
     * К каким командам подписан юзер
     */
    public function getTeamSubscriptions()
    {
        return $this->hasMany(Team::class, ['id' => 'team_id'])
            ->viaTable(UserSubscribe::tableName(), ['subscriber_id' => 'id']);
    }

	public static function serializeItem($user): array {

		return [
			'id'          => $user->id,
			'username'    => $user->username,
			'email'       => $user->email,
			'phone'       => $user->phone,
			'sms_confirm' => $user->sms_confirm,
			'date'        => [
				'created' => DateHelper::formatApi($user->created_at),
				'updated' => DateHelper::formatApi($user->updated_at),
			],
			'status'      => [
				'code' => $user->status,
				'name' => UserHelper::statusName($user->status),
			],
			'profile'     => UserProfile::serializeItem($user->profile)
		];
	}


	//endregion

	public function isSmsConfirm() {
		return $this->sms_confirm == 1;
	}


	/**
	 * Онлайн ли
	 *
	 * @param float|int $seconds
	 *
	 * @return bool
	 */
	public function onlineCheck($seconds = 60 * 2) {
		return (is_integer($this->last_online) && ($this->last_online + $seconds) > time()) ? 1 : 0;
	}


	/**
	 *  Ставим онлайн
	 */
	public function onlineSet() {
		$this->online      = true;
		$this->last_online = time();
	}

}

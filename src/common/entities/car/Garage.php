<?php

namespace common\entities\car;

use api\traits\TApiModel;
use common\entities\team\Team;
use common\entities\user\User;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "garage".
 *
 * @property int $id
 * @property int $user_id
 * @property int $team_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Team $team
 * @property User $user
 * @property Car $car
 */
class Garage extends \yii\db\ActiveRecord {
	use TApiModel;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'garage';
	}

	public function behaviors() {
		return [
			TimestampBehavior::class,
			[
				'class' => SaveRelationsBehavior::class,
				'relations' => ['team', 'user', 'car'],
			],
		];
	}

	public function transactions()
	{
		return [
			self::SCENARIO_DEFAULT => self::OP_ALL,
		];
	}



	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_id', 'team_id', 'created_at', 'updated_at'], 'integer'],
			[['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id' => 'id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'         => 'ID',
			'user_id'    => 'User ID',
			'team_id'    => 'Team ID',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTeam() {
		return $this->hasOne(Team::class, ['id' => 'team_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne(User::class, ['id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCar() {
		return $this->hasOne(Car::class, ['id' => 'car_id']);
	}

}

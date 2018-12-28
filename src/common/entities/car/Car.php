<?php

namespace common\entities\car;

use api\exceptions\Http400Exception;
use api\traits\TApiModel;
use common\entities\file\Files;
use common\entities\team\Team;
use common\entities\user\User;
use lib\services\file\FileService;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "car".
 *
 * @property int $id
 * @property int $user_id
 * @property int $team_id
 * @property int $cb_id
 * @property int $cm_id
 * @property int $cc_id
 * @property int $ct_id
 * @property int $year
 * @property double $volume
 * @property double $horsepower
 * @property int $main_photo_id
 * @property int $equipment
 * @property int $information
 * @property int $created_at
 * @property int $updated_at
 *
 * @property CarBrand $cb
 * @property CarClass $cc
 * @property CarModel $cm
 * @property CarTransmission $ct
 * @property Files $mainPhoto
 * @property User $user
 */
class Car extends \yii\db\ActiveRecord {
	use TApiModel;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'car';
	}

	public function behaviors() {
		return [
			TimestampBehavior::class,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_id', 'cb_id', 'cm_id', 'cc_id', 'ct_id', 'year', 'main_photo_id', 'created_at', 'updated_at','team_id'], 'integer'],
			[['information', 'equipment'], 'string', 'max' => 500 ],
			[['information', 'equipment'], 'trim'],
			[['volume', 'horsepower'], 'number', 'numberPattern' => '/^\d+(.\d{1,2})?$/'],
			[['cb_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarBrand::class, 'targetAttribute' => ['cb_id' => 'id']],
			[['cc_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarClass::class, 'targetAttribute' => ['cc_id' => 'id']],
			[['cm_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarModel::class, 'targetAttribute' => ['cm_id' => 'id']],
			[['ct_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarTransmission::class, 'targetAttribute' => ['ct_id' => 'id']],
			[['main_photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['main_photo_id' => 'id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
			[['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'            => 'ID',
			'user_id'       => 'User ID',
			'cb_id'         => 'Cb ID',
			'cm_id'         => 'Cm ID',
			'cc_id'         => 'Cc ID',
			'ct_id'         => 'Ct ID',
			'year'          => 'Year',
			'volume'        => 'Volume',
			'horsepower'    => 'Horsepower',
			'main_photo_id' => 'Main Photo ID',
            'equipment'     => 'equipment',
            'information'   => 'information',
			'created_at'    => 'Created At',
			'updated_at'    => 'Updated At',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCb() {
		return $this->hasOne(CarBrand::class, ['id' => 'cb_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCc() {
		return $this->hasOne(CarClass::class, ['id' => 'cc_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCm() {
		return $this->hasOne(CarModel::class, ['id' => 'cm_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCt() {
		return $this->hasOne(CarTransmission::class, ['id' => 'ct_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMainPhoto() {
		return $this->hasOne(Files::class, ['id' => 'main_photo_id']);
	}

	public function getPhotos() {
		return $this->hasMany(Files::class, ['id' => 'photo_id'])
		            ->viaTable('car_photo', ['car_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne(User::class, ['id' => 'user_id']);
	}

	public static function serializeItem($item, $params = []) {
		$result = ArrayHelper::toArray($item);

//		ArrayHelper::remove($result, 'user_id');
		ArrayHelper::remove($result, 'created_at');
		ArrayHelper::remove($result, 'updated_at');

		if (in_array('full', $params)) {
			$result['cb_id']         = CarBrand::serializeItem($item->cb);
			$result['cm_id']         = CarModel::serializeItem($item->cm);
			$result['cc_id']         = CarClass::serializeItem($item->cc);
			$result['ct_id']         = CarTransmission::serializeItem($item->ct);
			$result['main_photo_id'] = Files::serializeItem($item->mainPhoto);
			$result['photos']        = Files::serialize($item->photos);
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function getPhotosIds() {
		return array_map(function ($item) {
			return $item->id;
		}, $this->photos);
	}

	public function linkPhotos(array $photos) {
		$carPhotoIds = $this->getPhotosIds();
		foreach ($photos as $item) {
			if ( ! in_array($item->id, $carPhotoIds)) {
				$this->link('photos', $item);
			}
		}
	}


    public static function findByIdAndTeamIdOrFail($id, $team_id)
    {
        $item = static::find()->where([
            'id' => $id,
            'team_id' => $team_id
        ])->limit(1)->one();

        if(!$item)
            throw new Http400Exception(static::shorClass() . ' not found');

        return $item;
    }
}

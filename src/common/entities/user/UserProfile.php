<?php

namespace common\entities\user;

use api\forms\ApiForm;
use common\entities\file\Files;
use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_profile".
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property int $country_id
 * @property int $city_id
 * @property int $photo_id
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 * @property Cities $city
 * @property Countries $country
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'country_id', 'city_id', 'created_at', 'updated_at', 'photo_id'], 'integer'],
            [['first_name', 'last_name','organizer_name','organizer_legal_name', 'organizer_address','organizer_legal_address'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 340],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'city_id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::class, 'targetAttribute' => ['country_id' => 'country_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'user_id'     => 'User ID',
            'first_name'  => 'First Name',
            'last_name'   => 'Last Name',
            'country_id'  => 'Country ID',
            'city_id'     => 'City ID',
            'photo_id'    => 'Photo ID',
            'description' => 'Description',
            'organizer_name' => 'organizer_name',
            'organizer_legal_name' => 'organizer_legal_name',
            'organizer_address' => 'organizer_address',
            'organizer_legal_address' => 'organizer_legal_address',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['city_id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::class, ['country_id' => 'country_id']);
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRegion()
	{
		return $this->hasOne(Regions::class, ['region_id' => 'region_id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getPhoto()
    {
        return $this->hasOne(Files::class, ['id' => 'photo_id']);
    }

    /**
     * @param UserProfile $item
     *
     * @return array|null
     */
    public static function serializeItem($item)
    {
        if (!$item)
            return null;

        $role = $item->user->getFirstRoleName();

        $all = [
	        'first_name'  => $item->first_name,
	        'last_name'   => $item->last_name,
	        'description' => $item->description,
	        'country'     => Countries::serialize($item->country),
	        'city'        => Cities::serializeItem($item->city),
	        'region'      => Regions::serializeItem($item->region),
	        'photo'       => Files::serializeItem($item->photo),
        ];

//        if($role == User::R_RACER){
//        	return ArrayHelper::merge($all,[
//
//	        ]);
//        }
//
//	    if($role == User::R_GAPER){
//		    return ArrayHelper::merge($all,[
//
//		    ]);
//	    }

	    if ($role == User::R_ORGANIZER) {
		    return ArrayHelper::merge($all, [
			    'organizer_name'          => $item->organizer_name,
			    'organizer_legal_name'    => $item->organizer_legal_name,
			    'organizer_address'       => $item->organizer_address,
			    'organizer_address_index'       => $item->organizer_address_index,
			    'organizer_legal_address' => $item->organizer_legal_address,
			    'organizer_legal_address_index' => $item->organizer_legal_address_index,
		    ]);
	    }



	    if ($role == User::R_JOURNALIST) {
		    return ArrayHelper::merge($all, [
			    'organizer_name' => $item->organizer_name
		    ]);
	    }

        return $all;

    }


    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

}

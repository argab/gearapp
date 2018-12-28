<?php

namespace common\entities\event;

use api\traits\CounterTrait;
use api\traits\FavoriteTrait;
use api\traits\TApiModel;
use common\base\ActiveRecord;
use common\dictionaries\EventType;
use common\dictionaries\Role;
use common\entities\file\Files;
use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\queries\EventQuery;
use common\entities\user\User;
use common\entities\user\UserFavorite;
use common\traits\PhotosTrait;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $owner_id
 * @property int $country_id
 * @property int $city_id
 * @property int $region_id
 * @property string $latitude
 * @property string $longitude
 * @property int $photo_id
 * @property int $type
 * @property int $is_hide
 * @property string $event_date_start
 * @property string $event_date_end
 * @property int $views
 * @property int $likes
 * @property int $shares
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Cities $city
 * @property Countries $country
 * @property Files $photo
 * @property User $owner
 * @property Regions $region
 */
class Event extends ActiveRecord
{
    use PhotosTrait,
        TApiModel,
        CounterTrait,
        FavoriteTrait
        ;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'owner_id'], 'required'],
            [['description'], 'string'],
            [['owner_id', 'country_id', 'city_id', 'region_id', 'photo_id', 'type', 'is_hide', 'views', 'likes', 'shares', 'created_at', 'updated_at'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['event_date_start', 'event_date_end'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'city_id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['photo_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::className(), 'targetAttribute' => ['region_id' => 'region_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'owner_id' => 'Owner ID',
            'country_id' => 'Country ID',
            'city_id' => 'City ID',
            'region_id' => 'Region ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'photo_id' => 'Photo ID',
            'type' => 'Type',
            'is_hide' => 'Is Hide',
            'event_date_start' => 'Event Date Start',
            'event_date_end' => 'Event Date End',
            'views' => 'Views',
            'likes' => 'Likes',
            'shares' => 'Shares',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['city_id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Files::className(), ['id' => 'photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::className(), ['region_id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites($user_id = null)
    {
        $user_id = $user_id ?? User::authUser()->id;

        return $this->hasMany(UserFavorite::class, ['model_id' => 'id'])
            ->where([
                'user_favorite.model'   => Event::class,
                'user_favorite.user_id' => $user_id
            ]);
    }
    
    /**
     * @return EventQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return (new EventQuery(get_called_class()));
    }

    public static function serializeItem($item, $params = [])
    {
        $data = ArrayHelper::toArray($item);

        $data['owner'] = User::serializeItem($item->owner);
        $data['photo'] = Files::serializeItem($item->photo);
        $data['photos'] = Files::serialize($item->photosFile);
        $data['type'] = EventType::get($data['type']);
        $data['class'] = 'Event';
        $data['city'] = Cities::serializeItem($item->city);
        $data['country'] = Countries::serializeItem($item->country);

        return $data;
    }

    public function isOwner($id = null)
    {
        return $this->owner_id == ($id ?? User::authUser()->id);
    }

    public function canUpdate()
    {
        return $this->isOwner() || User::authUser()->hasRole([Role::R_ADMIN]);
    }

    public function canDelete()
    {
        return $this->isOwner() || User::authUser()->hasRole([Role::R_ADMIN]);
    }
}

<?php

namespace common\entities\views;

use api\traits\CounterTrait;
use api\traits\FavoriteTrait;
use api\traits\TApiModel;
use common\entities\file\Files;
use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\news\News;
use common\entities\news\NewsTag;
use common\entities\queries\ViewNewsEventsQuery;
use common\entities\user\User;
use common\entities\user\UserFavorite;
use common\traits\PhotosTrait;
use Yii;

/**
 * This is the model class for table "view_news_events".
 *
 * @property int $id
 * @property string $class
 * @property string $title
 * @property string $description
 * @property int $owner_id
 * @property int $country_id
 * @property int $city_id
 * @property int $photo_id
 * @property int $type
 * @property int $views
 * @property int $likes
 * @property int $shares
 * @property int $created_at
 * @property string $latitude
 * @property string $longitude
 * @property int $is_hide
 * @property string $event_date_start
 * @property string $event_date_end
 * @property int $status
 * @property string $post_date
 * @property string $post_date_close
 */
class ViewNewsEvents extends \yii\db\ActiveRecord
{
    const CLASS_EVENT = 'Event';
    const CLASS_NEWS = 'News';

    use TApiModel,
        PhotosTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_news_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'owner_id', 'country_id', 'city_id', 'photo_id', 'type', 'views', 'likes', 'shares', 'created_at', 'is_hide', 'status'], 'integer'],
            [['description'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['event_date_start', 'event_date_end', 'post_date', 'post_date_close'], 'safe'],
            [['class'], 'string', 'max' => 5],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class' => 'Class',
            'title' => 'Title',
            'description' => 'Description',
            'owner_id' => 'Owner ID',
            'country_id' => 'Country ID',
            'city_id' => 'City ID',
            'photo_id' => 'Photo ID',
            'type' => 'Type',
            'views' => 'Views',
            'likes' => 'Likes',
            'shares' => 'Shares',
            'created_at' => 'Created At',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'is_hide' => 'Is Hide',
            'event_date_start' => 'Event Date Start',
            'event_date_end' => 'Event Date End',
            'status' => 'Status',
            'post_date' => 'Post Date',
            'post_date_close' => 'Post Date Close',
        ];
    }


    /**
     * @return ViewNewsEventsQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return (new ViewNewsEventsQuery(get_called_class()));
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
    public function getTags()
    {
        return $this->hasMany(NewsTag::className(), ['news_id' => 'id']);
    }


}

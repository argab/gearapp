<?php

namespace common\entities\news;

use api\forms\GeoForm;
use api\forms\news\NewsFeedbackCreateForm;
use api\forms\PhotosForm;
use api\traits\CounterTrait;
use api\traits\FavoriteTrait;
use api\traits\TApiModel;
use common\base\ActiveRecord;
use common\base\AppHelper;
use common\base\Assert;
use common\dictionaries\NewsStatus;
use common\dictionaries\NewsType;
use common\dictionaries\Role;
use common\entities\file\Files;
use common\entities\file\Photos;
use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\queries\NewsQuery;
use common\entities\team\Team;
use common\entities\user\User;
use common\entities\user\UserFavorite;
use common\traits\PhotosTrait;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "news".
 * @property int $id
 * @property string $title
 * @property int $owner_id
 * @property int $team_id
 * @property int $country_id
 * @property int $city_id
 * @property int $region_id
 * @property string $description
 * @property int $photo_id
 * @property int $status
 * @property int $type
 * @property int $post_date
 * @property int $post_date_close
 * @property int $created_at
 * @property int $updated_at
 * @property int $views
 * @property int $likes
 * @property int $shares
 * @property Cities $city
 * @property Countries $country
 * @property Files $photo
 * @property User $owner
 * @property Regions $region
 * @property Team $team
 */
class News extends ActiveRecord
{
    use TApiModel,
        PhotosTrait,
        CounterTrait,
        FavoriteTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
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
            // geo rules
            [['city_id', 'country_id', 'region_id'], 'integer'],
            [['city_id', 'country_id', 'region_id'], 'required'],
            [['city_id'], 'exist', 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'city_id']],
            [['country_id'], 'exist', 'targetClass' => Countries::class, 'targetAttribute' => ['country_id' => 'country_id']],
            [['region_id'], 'exist', 'targetClass' => Regions::class, 'targetAttribute' => ['region_id' => 'region_id']],

            //photo rules
            [['photo_id'], 'exist', 'targetClass' => Files::class, 'targetAttribute' => ['photo_id' => 'id']],

            //news rules
            [['owner_id', 'status', 'type', 'views', 'likes', 'shares', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'type',], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
            [
                ['post_date', 'post_date_close'], 'datetime',
                'format'   => 'php:Y-m-d H:i:s',
//                'min'      => gmdate('Y-m-d H:i:s'),
//                'tooSmall' => Yii::t('app', '{attribute} must be no less than {min}.', ['min' => Yii::t('app', 'current time')])
            ]


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'title'       => 'Title',
            'owner_id'    => 'Owner ID',
            'country_id'  => 'Country ID',
            'city_id'     => 'City ID',
            'region_id'   => 'Region ID',
            'description' => 'Description',
            'photo_id'    => 'Photo ID',
            'status'      => 'Status',
            'type'        => 'Type',
            'post_date'   => 'Post Date',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
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
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites($user_id = null)
    {
        $user_id = $user_id ?? User::authUser()->id;

        return $this->hasMany(UserFavorite::class, ['model_id' => 'id'])
            ->where([
                'user_favorite.model'   => News::class,
                'user_favorite.user_id' => $user_id
            ]);
    }

    public function attachTags($tags)
    {
        if (empty($tags))
            return;

        $tags = array_unique($tags);

        foreach ($tags as $tag)
        {
            $item = new NewsTag([
                'news_id' => $this->id,
                'name'    => $tag
            ]);
            Assert::true($item->save());
        }
    }


    /**
     * @return NewsQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return (new NewsQuery(get_called_class()));
    }

    public static function findActive($id, $with = [
        'tags',
        'owner.profile',
        'photo',
        'photos.file'
    ]): ?self
    {
        return self::find()
            ->with($with)
            //            ->moderated()
            //            ->posted()
            ->byId($id)
            ->one();
    }

    public static function serializeItem($item, $params = [])
    {
        $data = ArrayHelper::toArray($item);

        $data['class'] = 'News';
        $data['tags'] = ArrayHelper::getColumn($item->tags, 'name');
        $data['owner'] = User::serializeItem($item->owner);
        $data['photo'] = Files::serializeItem($item->photo);
        $data['photos'] = Files::serialize($item->photosFile);
        $data['status_name'] = NewsStatus::get($data['status']);
        $data['type_name'] = NewsType::get($data['type']);
        $data['city'] = Cities::serializeItem($item->city);
        $data['country'] = Countries::serializeItem($item->country);

        return $data;
    }

    public function statusIs($status)
    {
        return $this->status == $status;
    }

    public function typeIs($type)
    {
        return $this->type == $type;
    }

    public function detachTags()
    {
        $this->unlinkAll('tags', true);
    }

    public function canModerate()
    {
        return $this->status == NewsStatus::IN_MODERATION;
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
    
    public function setReject()
    {
        $this->status = NewsStatus::REJECTED;
        $this->save();
    }
    
    public function setPublic()
    {
        $this->status = NewsStatus::PUBLISHED;
        $this->save();
    }

}

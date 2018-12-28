<?php

namespace common\entities\team;

use api\exceptions\Http400Exception;
use api\traits\TApiModel;
use common\base\ActiveRecord;
use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;
use common\entities\team\TeamHistory;
use common\entities\car\Car;
use common\entities\file\Files;
use common\entities\user\User;
use common\entities\user\UserSubscribe;
use common\traits\GeoRelationTrait;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lib\services\team\TeamHistoryService;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "team".
 * @property int $id
 * @property int $creator_id
 * @property string $title
 * @property string $description
 * @property string $photo_id
 * @property int $created_at
 * @property int $updated_at
 * @property User $creator
 * @property TeamMembers[] $teamMembers
 * @property $garage
 * @property $cars
 */
class Team extends ActiveRecord
{

    use TApiModel, GeoRelationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class'     => SaveRelationsBehavior::class,
                'relations' => ['garage', 'history'],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_INSERT, function($event){
            TeamHistoryService::eventInsertTeam($event);
        });
        $this->on(self::EVENT_BEFORE_UPDATE, function($event){
            TeamHistoryService::eventUpdateTeam($event);
        });
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
    public function rules()
    {
        return [
            [['creator_id', 'created_at', 'updated_at', 'region_id', 'city_id', 'country_id'], 'integer'],
            [['description'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 255],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['photo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'creator_id' => 'Creator ID',
            'title'      => 'Title',
            'description'      => 'Description',
            'photo_id'   => 'Photo Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }



    //region Relations

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamMembers()
    {
        return $this->hasMany(TeamMembers::class, ['team_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Files::class, ['id' => 'photo_id']);
    }

    public function getCars() {
        return $this->hasMany(Car::class, ['team_id' => 'id']);
    }

    public function getGarage()
    {
        return $this->hasMany(Car::class, ['id' => 'car_id'])
            ->viaTable('garage', ['team_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistory()
    {
        return $this->hasMany(TeamHistory::class, ['team_id' => 'id']);
    }

    public function getSubscribers()
    {
        return $this->hasMany(User::class, ['id' => 'team_id'])
            ->viaTable(UserSubscribe::tableName(), ['subscriber_id' => 'id']);
    }

    //endregion


    public static function serializeItem($item, $params = [])
    {
        $result = [
            'id'          => $item->id,
            'title'       => $item->title,
            'description' => $item->description,
            'creator_id'  => $item->creator_id,
            'country'     => Countries::serialize($item->country),
            'city'        => Cities::serializeItem($item->city),
            'region'      => Regions::serializeItem($item->region),
            'photo'       => Files::serializeItem($item->photo),
        ];

        if (in_array('full', $params))
        {
            $result = array_merge($result, [
                'creator' => User::serializeItem($item->creator),
                'members'    => TeamMembers::serialize($item->teamMembers),
            ]);
        }

        return $result;
    }

    public function searchFields()
    {
        return [
            'creator_id',
            'title',
            'description',
            'region_id',
            'city_id',
            'country_id'
        ];
    }

    public static function search($params)
    {
        $items = self::find()->limit(10);
        $items = self::andFilterWhereLikeByParams($items, $params);

        return $items->all();
    }

    /**
     * @param null $user_id
     *
     * @return bool|int
     */
    public function userIsCreator($user_id = null)
    {
        return $this->creator_id == ($user_id ?: User::authUser()->id);
    }


    //region History
    public function historyCreateTeam()
    {

    }
    //endregion


    /**
     * @param $user_id
     *
     * @return TeamMembers
     * @throws Http400Exception
     */
    public function getMemberByUserId($user_id)
    {
        foreach ($this->teamMembers as $item)
        {
            if ($item->user_id == $user_id) return $item;
        }

        throw new Http400Exception('User not found in team');
    }


    public function userIsOwner($id)
    {
        return $this->creator_id == $id;
    }

    public function failIfAuthUserNotOwner()
    {
        if (!$this->userIsOwner(User::authUser()->id))
            throw new Http400Exception('У вас не достаточно прав');
    }

}

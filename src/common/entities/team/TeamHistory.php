<?php

namespace common\entities\team;

use api\traits\TApiModel;
use common\entities\file\Files;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "team_history".
 * @property int $id
 * @property int $team_id
 * @property int $type
 * @property string $title
 * @property string $description
 * @property int $photo_id
 * @property string $event_date
 * @property int $created_at
 * @property int $updated_at
 * @property Files $photo
 * @property Team $team
 */
class TeamHistory extends \yii\db\ActiveRecord
{
    use TApiModel;

    const T_CREATED = 0;
    const T_NAME_CHANGE = 2;
    const T_PHOTO_CHANGE = 3;
    const T_MEMBER_ADD = 4;
    const T_MEMBER_REMOVE = 5;

    public static function types($type)
    {
        $temp =  [
            self::T_CREATED       => 'created',
            self::T_NAME_CHANGE   => 'name_change',
            self::T_PHOTO_CHANGE  => 'photo_change',
            self::T_MEMBER_ADD    => 'member_add',
            self::T_MEMBER_REMOVE => 'member_remove',
        ];

        return $temp[$type];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team_history';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class'     => SaveRelationsBehavior::class,
                'relations' => ['garage'],
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
    public function rules()
    {
        return [
            [['team_id'], 'required'],
            [['team_id', 'photo_id', 'created_at', 'updated_at', 'type', 'event_date'], 'integer'],
            [['description'], 'string'],
            [['event_date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['photo_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'team_id'     => 'Team ID',
            'title'       => 'Title',
            'description' => 'Description',
            'photo_id'    => 'Photo ID',
            'event_date'  => 'Event Date',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Files::class, ['id' => 'photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }


    public static function createWithoutSave(
        $type,
        $title = '',
        $description = '',
        $photo_id = null,
        integer $event_time = null
    ): self
    {
        $item = new self();
        $item->type = $type;
        $item->title = $title;
        $item->description = $description;
        $item->event_date = $event_time ? $event_time : time();

        if ($photo_id)
        {
            $item->photo_id = $photo_id;
        }

        return $item;
    }

    public static function serializeItem($item, $params = [])
    {
        $all = [
            'type'        => self::types($item->type),
            'title'       => $item->title,
            'description' => $item->description,
            'event_date' => $item->event_date,
            'photo'       => Files::serializeItem($item->photo),
        ];

        return $all;
    }


}

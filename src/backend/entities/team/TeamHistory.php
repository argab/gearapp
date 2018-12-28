<?php

namespace backend\entities\team;

use common\entities\file\Files;
use common\entities\team\Team;
use Yii;

/**
 * This is the model class for table "team_history".
 *
 * @property int $id
 * @property int $type
 * @property int $team_id
 * @property string $title
 * @property string $description
 * @property int $photo_id
 * @property int $event_date
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Files $photo
 * @property Team $team
 */
class TeamHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'team_id', 'photo_id', 'event_date', 'created_at', 'updated_at'], 'integer'],
            [['team_id', 'event_date'], 'required'],
            [['description'], 'string'],
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
            'id' => 'ID',
            'type' => 'Type',
            'team_id' => 'Team ID',
            'title' => 'Title',
            'description' => 'Description',
            'photo_id' => 'Photo ID',
            'event_date' => 'Event Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
}

<?php

namespace common\entities\team;

use api\traits\TApiModel;
use common\entities\user\User;
use lib\services\team\TeamHistoryService;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "team_members".
 * @property int $team_id
 * @property int $user_id
 * @property string $user_label
 * @property int $user_role
 * @property int $created_at
 * @property int $updated_at
 * @property Team $team
 * @property User $user
 */
class TeamMembers extends \yii\db\ActiveRecord
{
    use TApiModel;

    const L_CREATOR = "creator";
    const L_RACER = "racer";
    const L_JOURNALIST = "journalist";
    const L_TECHNICIAN = "technician";
    const L_MEDIC = "medic";
    const L_ADMINISTRATOR = "administrator";
    const L_MARKETER = "marketer";
    const L_HR = "hr";
    const L_LOGIST = "logist";

    const R_PARTICIPANT = 0;
    const R_CREATOR = 9;


    public static function user_labels()
    {
        return [
            self::L_RACER         => "Гонщик",
            self::L_JOURNALIST    => "Журналист",
            self::L_TECHNICIAN    => "Техник",
            self::L_MEDIC         => "Медик",
            self::L_ADMINISTRATOR => "Администратор",
            self::L_MARKETER      => "Маркетолог",
            self::L_HR            => "HR",
            self::L_LOGIST        => "Логист",
        ];
    }

	public function behaviors() {
		return [
			TimestampBehavior::class,
		];
	}

	public function init() {
		parent::init();
		$this->on(self::EVENT_BEFORE_INSERT, function ($event) {
			TeamHistoryService::eventInsertTeamMember($event);
		});
		$this->on(self::EVENT_BEFORE_UPDATE, function ($event) {
			TeamHistoryService::eventUpdateTeamMember($event);
		});
		$this->on(self::EVENT_BEFORE_DELETE, function ($event) {
			TeamHistoryService::eventDeleteTeamMember($event);
		});
	}

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team_members';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['user_label'], 'string', 'max' => 255],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'team_id'    => 'Team ID',
            'user_id'    => 'User ID',
            'user_label' => 'User Label',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->with('profile');
    }

    public static function serializeItem($item)
    {
        return [
//            'team'       => Team::serializeItem($item->team),
            'user'       => User::serializeItem($item->user),
            'user_label' => $item->user_label
        ];
    }


}

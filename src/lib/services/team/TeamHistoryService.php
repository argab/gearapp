<?php

namespace lib\services\team;


use common\entities\team\Team;
use common\entities\team\TeamHistory;
use common\entities\team\TeamMembers;
use yii\base\Event;

class TeamHistoryService
{


    //region Team
    /**
     * @param Event $event
     *
     * @return void
     */
    public static function eventUpdateTeam(Event $event)
    {

        /** @var Team $team */
        $team = $event->sender;

        $newAttributes = $team->getAttributes();
        $oldAttributes = $team->getOldAttributes();
        $dirtyAttributes = $team->getDirtyAttributes();

        if (array_key_exists('title', $dirtyAttributes))
            self::changedTeamName($team, $newAttributes['title'], $oldAttributes['title']);

        if (array_key_exists('photo_id', $dirtyAttributes))
            self::changedTeamPhoto($team, $newAttributes['photo_id'], $oldAttributes['photo_id']);
    }

    public static function eventInsertTeam(Event $event)
    {
        $team = $event->sender;
        if ($team->getIsNewRecord())
        {
            self::teamCreated($team);
        }
    }

    public static function teamCreated($team): void
    {
        $item[] = TeamHistory::createWithoutSave(
            TeamHistory::T_CREATED
        );

        $team->history = $item;
    }

    public static function changedTeamName($team, $new, $old)
    {
        $items = $team->history;
        $items[] = TeamHistory::createWithoutSave(
            TeamHistory::T_NAME_CHANGE,
            "Название изменилось с '$old' на '$new'"
        );
        $team->history = $items;

        //		Если будет слишком много истории команды
        //		$item = TeamHistory::createWithoutSave(
        //			TeamHistory::T_NAME_CHANGE,
        //			"Название изменилось с '$old' на '$new'"
        //		);
        //		$item->team_id = $team->id;
        //		$item->saveOrFail();
    }

    public static function changedTeamPhoto($team, $new, $old)
    {
        $items = $team->history;
        $items[] = TeamHistory::createWithoutSave(
            TeamHistory::T_PHOTO_CHANGE,
            "Изменилась фото команды"
        );
        $team->history = $items;

        //		Если будет слишком много истории команды
        //		$item = TeamHistory::createWithoutSave(
        //			TeamHistory::T_PHOTO_CHANGE,
        //			"Изменилась фото команды"
        //		);
        //		$item->team_id = $team->id;
        //		$item->saveOrFail();


    }
    //endregion


    //region TeamMembers
    public static function eventInsertTeamMember(Event $event)
    {
        /** @var TeamMembers $member */
        $member = $event->sender;
        $user = $member->user;
        $team = $member->team;

        self::teamMemberCreated($team, $user, $member);

    }

    public static function eventUpdateTeamMember(Event $event)
    {
        /** @var Team $team */
        $item = $event->sender;

        $newAttributes = $item->getAttributes();
        $oldAttributes = $item->getOldAttributes();
        $dirtyAttributes = $item->getDirtyAttributes();

        if (array_key_exists('user_label', $dirtyAttributes))
            self::changedTeamMemberName($item, $newAttributes['user_label'], $oldAttributes['user_label']);


    }

    public static function eventDeleteTeamMember(Event $event)
    {
        /** @var TeamMembers $member */
        $member = $event->sender;
        $user = $member->user;
        $team = $member->team;

        self::teamMemberDeleted($team, $user, $member);
    }

    public static function changedTeamMembers($team, $new, $old)
    {

    }


    public static function teamMemberCreated($team, $user, $member): void
    {
        $userName = $user->profile->getFullName();
        $teamName = $team->title;
        $userLabel = $member->user_label;
        $item = TeamHistory::createWithoutSave(
            TeamHistory::T_MEMBER_ADD,
            "Участник '$userName' добавлен в комманду '$teamName' с ролью '$userLabel'"
        );
        $item->team_id = $team->id;
        $item->saveOrFail();
    }

    public static function teamMemberDeleted($team, $user, $member): void
    {
        $userName = $user->profile->getFullName();
        $teamName = $team->title;

        $item = TeamHistory::createWithoutSave(
            TeamHistory::T_MEMBER_REMOVE,
            "Участник '$userName' удален из комманды '$teamName'"
        );
        $item->team_id = $team->id;
        $item->saveOrFail();
    }

    public static function changedTeamMemberName($member, $new, $old)
    {
        $user = $member->user;
        $userName = $user->profile->getFullName();

        $item = TeamHistory::createWithoutSave(
            TeamHistory::T_MEMBER_ADD,
            "Участник '$userName' изменил свою роль в команде с '$old' на '$new'"
        );
        $item->team_id = $member->team_id;
        $item->saveOrFail();


    }
    //endregion
}

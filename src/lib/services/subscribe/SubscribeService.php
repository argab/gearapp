<?php

namespace lib\services\subscribe;

use common\entities\user\UserSubscribe;

class SubscribeService
{

    //region Check
    public static function checkIfUserSubscribedToTeam($team_id, $subscriber_id)
    {
        return UserSubscribe::findBySubscriberIdAndWhere($subscriber_id, [
            'team_id' => $team_id
        ]);
    }

    public static function checkIfUserSubscribedToUser($user_id, $subscriber_id)
    {
        return UserSubscribe::findBySubscriberIdAndWhere($subscriber_id, [
            'user_id' => $user_id
        ]);
    }
    //endregion


    //region Subscribe
    public static function subscribeUserToTeam($subscriber_id, $team_id)
    {
        $item = UserSubscribe::subscribeToTeam($subscriber_id,$team_id);
        $item->saveOrFail();
    }

    public static function subscribeToUser($subscriber_id, $user_id)
    {
        $item = UserSubscribe::subscribeToUser($subscriber_id, $user_id);
        $item->saveOrFail();
    }
    //endregion


    //region Unsubscribe
    public static function unsubscribeUserFromUser($user_id, $subscriber_id)
    {
        $item = self::checkIfUserSubscribedToUser($user_id, $subscriber_id);
        $item->deleteOrFail();
    }

    public static function unsubscribeUserFromTeam($subscriber_id, $team_id)
    {
        $item = self::checkIfUserSubscribedToTeam($team_id, $subscriber_id);
        $item->deleteOrFail();
    }
    //endregion
}

<?php

namespace lib\services\team;


use api\forms\user\team\TeamForm;
use common\entities\team\Team;
use common\entities\team\TeamMembers;
use common\entities\user\User;

class TeamService
{
    /**
     * @param User $user
     * @param string $title
     * @param string $description
     * @param $country_id
     * @param $region_id
     * @param $city_id
     * @param null $photo_id
     *
     * @return Team
     */
    public static function createTeam(
        User $user,
        string $title,
        string $description,
        $country_id,
        $region_id,
        $city_id,
        $photo_id = null
    )
    {
        $team = new Team([
            'creator_id'  => $user->id,
            'title'       => $title,
            'description' => $description,
            'country_id'  => $country_id,
            'region_id'   => $region_id,
            'city_id'     => $city_id,
        ]);

        if($photo_id)
            $team->photo_id = $photo_id;

        if(!$team->save())
            throw new \RuntimeException('Save error');

        $member = new TeamMembers();
        $member->team_id = $team->id;
        $member->user_id = $user->id;
        $member->user_label = TeamMembers::L_CREATOR;
        $member->user_role = TeamMembers::R_CREATOR;

        if(!$member->save())
            throw new \RuntimeException('Save error');

        return $team;
    }

    /**
     * Проверяет, есть ли такой пользователь в комманде
     * @param $team
     * @param $user_id
     *
     * @return bool
     */
    public static function checkIfTeamHasUserById($team, $user_id)
    {
        if (empty($items = $team->teamMembers))
            return false;

        foreach ($items as $item)
        {
            if ($item->user_id == $user_id)
                return true;
        }

        return false;
    }

	/**
	 * @param TeamForm $form
	 *
	 * @return mixed
	 */
	public static function updateTeam(TeamForm $form)
	{
	    $data = ['title' => $form->title];
	    if($form->photo_id)
	        $data['photo_id'] = $form->photo_id;

		$result = Team::updateById($form->id, $data);
	}


    public static function deleteMemberFromTeam($team, $user_id)
    {
        foreach ($team->teamMembers as $item){
            if($item->user_id == $user_id){
                if($item->delete())
                    return true;
            }
        }
        return false;
    }



	public static function addMemberToTeam($user_id, $team_id,$user_label, $user_role = TeamMembers::R_PARTICIPANT)
	{
		$team = new TeamMembers([
			'user_id'    => $user_id,
			'team_id'    => $team_id,
			'user_label' => $user_label,
			'user_role'  => $user_role
		]);

		if(!$team->save())
			throw new \RuntimeException('Save error');
	}

}

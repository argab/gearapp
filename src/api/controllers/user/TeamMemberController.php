<?php

namespace api\controllers\user;

use api\exceptions\Http400Exception;
use api\forms\user\team\TeamForm;
use api\forms\user\team\TeamMemberForm;
use api\traits\TApiProfileHttpAuth;
use common\entities\team\Team;
use common\entities\team\TeamMembers;
use common\entities\user\User;
use lib\filters\EmptyProfileFilter;
use lib\filters\EmptyRoleFilter;
use lib\filters\PhoneFilledFilter;
use lib\helpers\Response;
use lib\services\team\TeamService;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class TeamMemberController extends Controller
{
    public function behaviors()
    {
        return [
            "authenticator" => HttpBearerAuth::class,
            PhoneFilledFilter::class,
            EmptyRoleFilter::class,
            EmptyProfileFilter::class,
        ];
    }

    public function actionLabels()
    {
        $items = TeamMembers::user_labels();

        return Response::responseItems(
            array_key_value_wrap($items)
        );
    }

    /**
     * @param $team_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     */
    public function actionIndex($team_id)
    {
        $item = Team::findByIdOrFail($team_id);

        return Response::responseItems(
            TeamMembers::serialize($item->teamMembers)
        );
    }


    /**
     * @param $team_id
     * @param $user_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     */
    public function actionCreate($team_id, $user_id)
    {
        $form = new TeamMemberForm();
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        $item = Team::findByIdOrFail($team_id);

        if (TeamService::checkIfTeamHasUserById($item, $user_id))
            throw new Http400Exception('Пользователь уже в комманде');

        $user = User::findByIdOrFail($user_id);

        TeamService::addMemberToTeam($user_id, $team_id, $form->user_label);

        return Response::success([]);
    }


    /**
     * @param $team_id
     * @param $user_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     * @throws \api\exceptions\NotFoundException
     */
    public function actionUpdate($team_id, $user_id)
    {
        $form = new TeamMemberForm();
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        $team = Team::findByIdOrFail($team_id);

        $member = $team->getMemberByUserId($user_id);

        $member->user_label = $form->user_label;
        $member->saveOrFail();

        return Response::success([]);
    }


    /**
     * @param $team_id
     * @param $user_id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     * @throws \api\exceptions\NotFoundException
     */
    public function actionDelete($team_id, $user_id)
    {
        $item = Team::findByIdOrFail($team_id);

        if ( ! $item->teamMembers)
            throw new Http400Exception('В команде нет участников');

        if ( ! TeamService::checkIfTeamHasUserById($item, $user_id))
            throw new Http400Exception('Пользователя нет комманде');

        if ( ! $item->userIsCreator())
            throw new Http400Exception('Пользователь не может удалять членов команды');


        if ( ! TeamService::deleteMemberFromTeam($item, $user_id))
            throw new Http400Exception('Ошибка удаления');

        return Response::success([]);


        //	    if(!$item = Team::findById($id))
        //		    throw new Http400Exception('Incorrect id');
        //
        //	    if(!$item->delete())
        //		    throw new Http400Exception('Delete error');
        //
        //	    return Response::success([]);
    }

}

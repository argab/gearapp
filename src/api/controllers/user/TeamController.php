<?php

namespace api\controllers\user;

use api\exceptions\Http400Exception;
use api\forms\user\team\TeamForm;
use api\traits\TApiProfileHttpAuth;
use common\entities\team\Team;
use common\entities\user\User;
use lib\helpers\Response;
use lib\services\team\TeamService;
use yii\rest\Controller;
use api\traits\TApiRestController;

class TeamController extends Controller
{
    use TApiRestController, TApiProfileHttpAuth;

    public function actionIndex()
    {
        $user = user::authUser();

        return Response::responseItems(
            Team::serialize($user->teams)
        );
    }

    public function actionCreate()
    {
        $form = new TeamForm();
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        $team = TeamService::createTeam(
            User::authUser(),
            $form->title,
            $form->description,
            $form->country_id,
            $form->region_id,
            $form->city_id,
            $form->photo_id
        );


        return Response::responseItem(
            Team::serialize($team),
            201
        );
    }

    public function actionUpdate($id)
    {
        $form = new TeamForm();
        $form->scenario = TeamForm::SCENARIO_UPDATE;
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        $item = Team::findByIdOrFail($id);
        $item->failIfAuthUserNotOwner();

        $item->load(array_filter($form->getAttributes()), '');

        $item->saveOrFail();

        return Response::responseItem(
            Team::serialize($item),
            200
        );
    }


    public function actionDelete($id)
    {
        $item = Team::findByIdOrFail($id);
        $item->failIfAuthUserNotOwner();
        $item->deleteOrFail();

        return Response::success([]);
    }

    /**
     * @param $id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\NotFoundException
     */
    public function actionInfo($id)
    {
        $item = Team::findByIdOrFail($id);

        return Response::responseItem(
            Team::serialize($item, ['full']),
            200
        );
    }


}

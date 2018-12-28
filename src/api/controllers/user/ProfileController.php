<?php

namespace api\controllers\user;

use api\exceptions\Http400Exception;
use api\forms\user\profile\ProfileForm;
use api\traits\TApiHttpAuth;
use common\entities\user\User;
use common\entities\user\UserProfile;
use api\traits\TApiRestController;
use lib\filters\EmptyProfileFilter;
use lib\filters\EmptyRoleFilter;
use lib\filters\PhoneFilledFilter;
use lib\helpers\Response;
use lib\helpers\UserHelper;
use lib\services\user\UserProfileService;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class ProfileController extends Controller
{
    use TApiRestController, TApiHttpAuth;

    protected function _behaviors(): array
    {
        return [
            PhoneFilledFilter::class,
            EmptyRoleFilter::class,
            [
                'class'  => EmptyProfileFilter::class,
                'except' => ['create', 'update']
            ]
        ];
    }

    public function actionIndex()
    {
        $user = User::authUser();

        return Response::responseItem(
            UserHelper::serializeProfile($user)
        );
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     */
    public function actionCreate()
    {
        $form = new ProfileForm();
        $form->role = User::authUser()->getFirstRoleName();
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        $service = new UserProfileService();

        if (User::authUser()->profile)
            throw new Http400Exception('Профиль уже существует, измените через PUT');

        $user = $service->createOrUpdateUserProfile(User::authUser(), $form);

        $user = User::findIdentity($user->id);

        return Response::responseItem(
            UserHelper::serializeProfile($user),
            201
        );

    }

    public function actionUpdate()
    {
        $form = new ProfileForm();
        $form->scenario = ProfileForm::SCENARIO_UPDATE;
        $form->role = User::authUser()->getFirstRoleName();
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        if ( ! User::authUser()->profile)
            throw new Http400Exception('Сначала заполните профиль');

        $service = new UserProfileService();
        $user = $service->createOrUpdateUserProfile(User::authUser(), $form);

        $user = User::findIdentity($user->id);

        return Response::responseItem(
            UserHelper::serializeProfile($user),
            201
        );
    }


}

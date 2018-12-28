<?php

namespace api\controllers;

use api\forms\sms\SetPasswordForm;
use api\traits\TApiHttpAuth;
use http\Exception\RuntimeException;
use lib\filters\EmptyProfileFilter;
use lib\filters\EmptyRoleFilter;
use lib\filters\PhoneFilledFilter;
use api\forms\auth\RoleForm;
use lib\helpers\Response;
use lib\helpers\UserHelper;
use common\entities\user\User;
use common\forms\auth\PhoneConfirmForm;
use lib\services\sms\ConfirmSmsService;
use api\traits\TApiRestController;
use Yii;
use yii\rest\Controller;

class UserController extends Controller
{
    use TApiRestController, TApiHttpAuth;

    protected function _behaviors()
    {
        return [
            ['class' => PhoneFilledFilter::class, 'except' => ['set-phone']],
            ['class' => EmptyRoleFilter::class, 'except' => ['set-phone', 'set-role', 'set-password']],
            ['class' => EmptyProfileFilter::class, 'except' => ['set-phone', 'set-role', 'set-password']],
        ];
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionInfo()
    {
        $user = UserHelper::serializeUser(User::authUser());

        return Response::success($user);
    }

    /**
     * 1.Отправка смс и запись в кэш
     * 2.Проверка кода, установка телефона
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionSetPhone()
    {
        $form = new PhoneConfirmForm();

        $form->scenario = PhoneConfirmForm::SCENARIO_SEND_SMS_TO_NEW_PHONE;
        $form->load(Yii::$app->request->post(), '');

        $form->validate();

        $service = new ConfirmSmsService($form->phone);
        $service->checkIfSmsSend();

        if (empty($service->code))
            return $service->sendConfirmSmsWithResponseByPhone();

        $form->scenario = PhoneConfirmForm::SCENARIO_CHECK_SMS_CODE;
        $form->load(Yii::$app->request->post(), '');

        $form->validate();

        $service->checkCodeByPhone($form->code);

        $user = User::authUser();
        $user->phone = $form->phone;
        $user->setSmsConfirmTrue();
        $user->save();

        return Response::success([
            'message' => 'Телефон успешно установлен'
        ]);

    }


    public function actionSetRole()
    {
        $form = new RoleForm();
        $form->load(Yii::$app->request->post(), '');
        $form->validate();

        $roles = $this->roleManager->getRoles();

        $user = User::authUser();
        if ( ! $user->isRoleEmpty())
        {
            return Response::responseError([
                'message' => 'У пользователя уже установлена роль',
                'role'    => $roles[$user->getFirstRoleName()],
            ]);
        }

        if (empty($form->role) || ! array_key_exists($form->role, $roles))
        {
            return Response::success([
                'message' => 'Выберите доступную роль',
                'roles'   => $roles
            ]);
        }

        $this->roleManager->assignRole($user->id, $form->role);

        return Response::responseSuccess([
            'message' => sprintf("Роль %s успешно установлена", $roles[$form->role])
        ], 201);

    }

    public function actionSearch()
    {
        $params = \Yii::$app->request->post();
        $items = User::searchWithSerialize($params);

        return Response::responseItems($items);
    }

    /**
     * Установка пароля
     * @return \yii\console\Response|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionSetPassword()
    {
        $form = new SetPasswordForm();
        $form->load(Yii::$app->request->post(), '');
        $form->validate();

        $user = User::authUser();
        $user->setPassword($form->password);
        if ( ! $user->save())
            throw new RuntimeException('Ошибка сохранения');

        return Response::success([
            'message' => 'Пароль сменен успешно',
        ]);
    }


}

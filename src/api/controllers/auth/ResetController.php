<?php

namespace api\controllers\auth;

use api\forms\auth\EmailForm;
use api\forms\auth\PhoneForm;
use api\forms\reset\TokenForm;
use api\forms\sms\SmsCodeForm;
use lib\helpers\Response;
use lib\services\reset\ResetByEmailService;
use common\entities\user\User;
use common\entities\user\Token;
use lib\services\sms\ConfirmSmsService;
use Yii;
use yii\rest\Controller;

class ResetController extends Controller
{

    public function behaviors()
    {
        return [];
    }

    public function actionByEmail()
    {
        $form = new EmailForm();
        $form->load(Yii::$app->request->post(), '');
        $form->validate();

        $service = new ResetByEmailService();
        $service->sendResetEmail($form->email);

        return Response::successMessage('Reset email send to: ' . $form->email);
    }

    public function actionByEmailConfirm()
    {
        $form = new TokenForm();
        $form->load(Yii::$app->request->get(), '');
        $form->validate();

        $service = new ResetByEmailService();

        $user = $service->validateToken($form->token);
        $service->clearToken($user);

        $token = Token::createToken($user);

        return Response::successCreated([
            'phone'  => $user->phone,
            'token'  => $token->token,
            'expire' => $token->expire_time
        ]);

    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionByPhone()
    {
        $form = new PhoneForm();
        $form->scenario = PhoneForm::SCENARIO_PHONE;
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();

        $service = new ConfirmSmsService($form->phone);

        return $service->sendConfirmSmsWithResponseByPhone();
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     * @throws \api\exceptions\Http400Exception
     * @throws \yii\base\Exception
     */
    public function actionByPhoneConfirm()
    {
        $form = new SmsCodeForm();
        $form->load(Yii::$app->request->post(), '');
        $form->validate();

        $service = new ConfirmSmsService($form->phone);

        $service->checkIfSmsSendWithException();

        $service->checkCodeByPhone($form->code);

        $service->clearSession();

        $user = User::findByPhone($form->phone);
        $token = Token::createToken($user);

        return Response::successCreated([
            'phone'  => $form->phone,
            'token'  => $token->token,
            'expire' => $token->expire_time
        ]);

    }

}

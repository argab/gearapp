<?php

namespace api\controllers\auth;

use api\exceptions\Http400Exception;
use api\forms\auth\LoginForm;
use api\forms\auth\PhoneForm;
use api\forms\auth\SignUpPhoneForm;
use api\forms\sms\SetPasswordForm;
use api\forms\sms\SmsCodeForm;
use lib\helpers\Links;
use lib\helpers\Response;
use common\entities\user\Token;
use common\entities\user\User;
use lib\services\auth\SignupService;
use lib\services\sms\ConfirmSmsService;
use SebastianBergmann\Timer\RuntimeException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class AuthController extends Controller
{

    public function behaviors()
    {
        return [
            //            "authenticator" => [
            //                'class' => HttpBearerAuth::class,
            //                'only' => ['sms-code-set-password']
            //            ]
        ];
    }

    public function actionLogin()
    {

        $form = new LoginForm();
        $form->load(Yii::$app->request->post(), '');
        $form->validate();

        $user = User::findByPhone($form->phone);

        return Token::createTokenAndSuccessResponse($user);
    }


    /**
     * @return \yii\console\Response|\yii\web\Response
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionSignup()
    {
        $form = new SignUpPhoneForm();
        $form->load(Yii::$app->request->post(), '');
        $form->validate();

        $user = User::findByPhone($form->phone);
        $service = new ConfirmSmsService($form->phone);

        if ( ! $user)
        {
            (new SignupService())->signup($form->phone);

            return $service->sendConfirmSmsWithResponseByPhone();
        }

        //        if ( ! $user->isSmsConfirm())
        return $service->sendConfirmSmsWithResponseByPhone();

        //        throw new Http400Exception('Пользователь подтвердил телефон. Возпользуйтесь восстановлением по номеру. /reset/phone');
    }

    /**
     * Подтверждение кода
     * @return \yii\console\Response|\yii\web\Response
     * @throws Http400Exception
     * @throws \yii\base\Exception
     */
    public function actionSmsCodeConfirm()
    {
        $form = new SmsCodeForm();
        $form->load(Yii::$app->request->post(), '');
        $form->validate();

        $service = new ConfirmSmsService($form->phone);

        $service->checkIfSmsSendWithException();

        $service->checkCodeByPhone($form->code);

        $user = User::findByPhone($form->phone);
        $user->setSmsConfirmTrue();

        $token = Token::createToken($user);
        $service->clearSession();

        return Response::success([
            'token'   => $token->token,
            'message' => 'Успешно, теперь создайте новый пароль',
            '_links'  => Links::get(['password' => ['user/set-password']])
        ]);

    }


    /**
     * Повторная отправка sms
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionSmsCodeResend()
    {
        $form = new PhoneForm();
        $form->scenario = PhoneForm::SCENARIO_PHONE;
        $form->load(Yii::$app->request->post(), '');
        $form->validate();

        $service = new ConfirmSmsService($form->phone);
        $service->resendSms($form->phone);

        return Response::success([
            'message' => 'Смс отправлена на номер ' . $form->phone
        ]);

    }

}

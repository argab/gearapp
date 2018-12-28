<?php

namespace frontend\controllers;


use common\forms\reset\RequestByEmailForm;
use common\forms\reset\RequestByPhoneForm;
use common\forms\reset\RequestByPhoneFormSetCode;
use common\forms\reset\RequestByPhoneFormSetPassword;
use common\forms\reset\SetPasswordByEmailForm;
use common\forms\reset\SetPasswordByPhoneForm;
use lib\services\auth\ResetPasswordByEmailService;
use lib\services\auth\ResetPasswordByPhoneService;
use Yii;
use yii\web\Controller;

class ResetController extends Controller
{
    /**
     * Запрос на воостановление пароля через email
     * @return string|\yii\web\Response
     */
    public function actionRequestEmail()
    {
        $form = new RequestByEmailForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            try
            {
                (new ResetPasswordByEmailService())->sendEmail($form);
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }
            catch (\DomainException $e)
            {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('requestByEmaill', [
            'model' => $form,
        ]);
    }

    /**
     * Восстановление пароля по токену email.
     *
     * @param string $token
     *
     * @return mixed
     */
    public function actionResetPassword($token)
    {
        try
        {
            $form = new SetPasswordByEmailForm($token);
        }
        catch (\DomainException $e)
        {
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->goHome();
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            try
            {
                (new ResetPasswordByEmailService())->resetPassword($form);
                Yii::$app->session->setFlash('success', 'New password saved.');
            }
            catch (\Exception $e)
            {
                Yii::$app->session->setFlash('error', $e->getMessage());
                $this->goBack();
            }

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $form,
        ]);
    }


    /**
     * Этап 1
     * Ввод номера и отправка смс
     * Запрос на воостановление через sms,
     * отправляет смс и делает запись в сессию и перекидывает на
     * ввод кода
     * @return string
     */
    public function actionRequestPhone()
    {
        $service = new ResetPasswordByPhoneService();
        if ($service->checkIfSmsSend())
        {
            $this->redirect('/reset/request-sms-code');
        }

        $form = new RequestByPhoneForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {

            $service->initByPhone($form->phone);
            if ( ! $service->checkIfSmsSend())
            {
                $service->generateAndSaveCode();
                try
                {
                    //					$msg = $service->sendSmsWithCode();
                    $msg = $service->code['code'];
                    Yii::$app->session->setFlash('success', $msg);
                    $this->redirect('/reset/request-sms-code');
                }
                catch (\DomainException $e)
                {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    $this->goBack();
                }
            }
        }

        return $this->render('requestByPhone', [
            'model' => $form,
        ]);
    }


    /**
     * Этап 2
     * Ввод и проверка кода
     * @return string
     */
    public function actionRequestSmsCode()
    {
        $service = new ResetPasswordByPhoneService();
        if ( ! $service->checkIfSmsSend())
        {
            Yii::$app->session->setFlash('error', 'Сессия истекла');
            $this->redirect('/reset/request-phone');
        }

        $form = new RequestByPhoneFormSetCode();
        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {

            if ($service->checkCodeFromForm($form->code))
            {
                Yii::$app->session->setFlash('success', 'Введите ваш новый пароль');
                $this->redirect('/reset/reset-password-sms');
            }
            else
            {
                Yii::$app->session->setFlash(
                    'error',
                    sprintf('Не верный код. У вас соталось: %d попытки %s', $service->code['throttle'], $service->code['code'])
                );
            };
        }

        return $this->render('requestSmsCode', [
            'model' => $form,
        ]);
    }

    /**
     * Этап 3
     * Сброс пароля
     * @return string
     */
    public function actionResetPasswordSms()
    {
        $service = new ResetPasswordByPhoneService();
        if ( ! $service->checkIfCodeConfirm())
        {
            Yii::$app->session->setFlash('error', 'Сессия истекла');
            $this->redirect('/reset/request-phone');
        }

        $form = new RequestByPhoneFormSetPassword();
        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            try
            {
                $service->resetPassword($form);
                Yii::$app->session->setFlash('success', 'New password saved.');
                $this->goHome();
            }
            catch (\DomainException $e)
            {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('resetPasswordSms', [
            'model' => $form,
        ]);
    }


}
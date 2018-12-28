<?php

namespace lib\services\auth;


use common\entities\user\User;
use common\forms\reset\RequestByPhoneForm;
use common\forms\reset\RequestByPhoneFormSetPassword;
use lib\services\sms\SmscKzService;
use SebastianBergmann\Timer\RuntimeException;
use Yii;
use yii\caching\Cache;

class ResetPasswordByPhoneService
{

    public $user;
    public $session;
    public $code;
    private $key;


    public function __construct()
    {
        $this->session = Yii::$app->session;

        $this->key = 'resetBySms';
    }

    public function initByPhone($phone)
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'phone'  => $phone,
        ]);

        if ( ! $user)
        {
            throw new \DomainException('User is not found.');
        }

        $this->user = $user;
    }


    public function checkCodeFromForm($codeFromForm)
    {
        if ( ! $this->checkIfSmsSend())
            return false;

        if ($this->code['code'] != $codeFromForm)
        {
            $this->code['throttle']--;
            $this->session->set($this->key, $this->code);

            return false;
        }

        $this->code['codeConfirm'] = true;
        $this->session->set($this->key, $this->code);

        return true;
    }

    /**
     * Этай проверки код пройден
     * @return bool
     */
    public function checkIfCodeConfirm()
    {
        if ( ! $this->checkIfSmsSend())
            return false;

        if ( ! $this->code['codeConfirm'])
        {
            $this->session->remove($this->key);

            return false;
        }

        return true;
    }

    public function generateAndSaveCode()
    {
        $code['phone'] = $this->user->phone;
        $code['code'] = random_int(100000, 999999);
        $code['throttle'] = 5;
        $code['expires_at'] = time() + get_params('user.passwordResetCodeExpire', 60 * 60);

        $this->session->set($this->key, $code);

        $this->code = $code;
    }

    public function sendSmsWithCode()
    {
        $msg = "Reset code: " . $this->code['code'];
        $sms = new SmscKzService($this->user->phone, get_params('sms.client'));
        $sms->send($msg);

        if ($sms->getErrorCode())
            throw new \DomainException('Ошибка отправки SMS: ' . $sms->getError());

        return $sms->getErrorCode()
            ?: 'Сообщение SMS успешно отправлено ('
            . 'Стоимость: ' . $sms->getStatus('cost')
            . '; Отправлено SMS: ' . $sms->getStatus('cnt')
            . '; Баланс: ' . $sms->getStatus('balance')
            . ')';
    }

    /**
     * Resets password.
     *
     * @param RequestByPhoneFormSetPassword $form
     */
    public function resetPassword(RequestByPhoneFormSetPassword $form)
    {
        if ( ! $this->checkIfCodeConfirm())
            throw new \DomainException('Сессия истекла');

        $phone = $this->code['phone'];
        $this->initByPhone($phone);

        $user = $this->user;
        $user->setPassword($form->password);
        $user->removePasswordResetToken();

        if ( ! $user->save(false))
            throw new \RuntimeException('Save error');

        $this->session->remove($this->key);

    }


}
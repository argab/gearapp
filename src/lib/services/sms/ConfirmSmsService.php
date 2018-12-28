<?php

namespace lib\services\sms;


use api\exceptions\Http400Exception;
use api\forms\auth\PhoneForm;
use api\forms\sms\SmsCodeForm;
use lib\helpers\Links;
use lib\helpers\Response;
use common\entities\user\User;
use SebastianBergmann\Timer\RuntimeException;
use Yii;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

class ConfirmSmsService
{

    public $storage;
    public $code;
    public $phone;
    public $storageKey;


    public function __construct($phone)
    {
        $this->storage = Yii::$app->cache;
        $this->phone = $phone;
        $this->storageKey = 'sms_confirm:' . preg_replace("/[^0-9]/", "",$phone);
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function sendConfirmSms()
    {
        //		$this->throttle();
        $this->generateAndSaveCode();
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function sendConfirmSmsWithResponseByPhone()
    {
        $this->sendConfirmSms();

        return Response::successCreated([
            'phone'   => $this->phone,
            'message' => 'Confirm sms sent to this number',
        ]);
    }

    /**
     * @param int $tryCount
     * @param float|int $banTime
     *
     * @throws ForbiddenHttpException
     */
    public function throttle($tryCount = 3, $banTime = 60 * 60)
    {
        $key = "phone_throttle:" . $this->phone;
        $throttle = $this->storage->get($key);

        if ( ! $throttle)
        {
            $throttle['count'] = 1;
            $throttle['banTimeEnd'] = time() + $banTime;
            $this->storage->set($key, $throttle, $banTime);

            return;
        }

        if ($throttle['count'] >= $tryCount)
            throw new ForbiddenHttpException('Попробуйте отправить через: ' . (int) $throttle['banTimeEnd'] - time() . ' секунд');

        $throttle['count']++;
        $throttle['banTimeEnd'] = time() + $banTime;
        $this->storage->set($key, $throttle, $banTime);

    }


	/**
	 * Проверка кода в сесии
	 * @throws Http400Exception
	 */
	public function checkIfSmsSend()
    {
        if ( ! $code = $this->storage->get($this->storageKey))
        	throw new Http400Exception($this->storageKey . ' ключ не найден');

        if ($code['throttle'] == 0)
        {
            $this->storage->delete($this->storageKey);
	        throw new Http400Exception('Истекло количестов попыток');
//            return false;
        }

        if (empty($code['expires_at']) || $code['expires_at'] < time())
	        throw new Http400Exception('Сессия истекла');

        $this->code = $code;
    }


    /**
     * Проверка с ошибкой
     * @throws Http400Exception
     */
    public function checkIfSmsSendWithException()
    {
        $this->checkIfSmsSend();
//            throw new Http400Exception('Сессия истекла, отправте смс повторно');

    }

    public function checkToken($token)
    {
        if ($this->code['codeConfirmToken'] == $token)
        {
            $this->storage->delete($this->storageKey);

            return true;
        }

        return false;
    }


    /**
     * @param $phone
     */
    public function resendSms($phone)
    {
        $this->generateAndSaveCode($phone);
    }

    /**
     * Генерирует код и ложит его в кэш
     */
    public function generateAndSaveCode()
    {
        $liveTime = get_params('user.passwordResetCodeExpire', 60 * 60 * 24);

        $code['phone'] = $this->phone;
        //		$code['code'] = random_int(100000, 999999);
        $code['code'] = 123456;
        $code['throttle'] = 5;
        $code['expires_at'] = time() + $liveTime;

        $this->storage->set($this->storageKey, $code, $liveTime);

        $this->code = $code;
    }


    /**
     * @param $code
     *
     * @throws Http400Exception
     * @throws \yii\base\Exception
     */
    public function checkCodeByPhone($code)
    {
        $this->checkIfSmsSend();

        if ($this->code['code'] != $code)
        {
            $this->code['throttle']--;
            $this->storage->set($this->storageKey, $this->code);
            throw new Http400Exception(sprintf('Не верный код. У вас осталось %d попыток', $this->code['throttle']));
        }

        $this->code['codeConfirm'] = true;
        $this->code['codeConfirmToken'] = Yii::$app->security->generateRandomString();

        $this->storage->set($this->storageKey, $this->code);
    }

    public function clearSession()
    {
        $this->storage->delete($this->storageKey);
    }

    public function setSmsConfirmTrueByPhoneNumber()
    {
        $user = User::findByPhone($this->phone);
        if ( ! $user)
            throw new RuntimeException('User not found');

        $user->setSmsConfirmTrue();
    }

}
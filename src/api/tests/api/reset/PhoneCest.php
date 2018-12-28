<?php

namespace api\tests\api;

use api\tests\ApiTester;
use common\entities\user\User;
use common\fixtures\UserFixture;
use function GuzzleHttp\Promise\all;
use Yii;
use yii\helpers\ArrayHelper;

class PhoneCest
{
    public $object;

    public function __construct()
    {
        $this->object = require codecept_data_dir() . 'userData.php';
    }


    public function actionSendRequestValidationError(ApiTester $I)
    {
        // проверка валидации
        $I->sendPOST('/reset/phone');
        d($I->grabResponse());
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    public function actionSendRequest(ApiTester $I)
    {
        // проверка валидации
        $I->sendPOST('/reset/phone', [
            'phone' => $this->object['phone']
        ]);
        d($I->grabResponse());
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        //		$token = $I->grabDataFromResponseByJsonPath('$.data.token[0]');
    }


    public function actionPhoneConfirmError(ApiTester $I)
    {
        // проверка валидации
        $I->sendPOST('/reset/phone-confirm', [
            'phone' => $this->object['phone'],
            'code'  => 'invalid_code'
        ]);
        d($I->grabResponse());
        $I->seeResponseCodeIs(400);
    }

    public function actionPhoneConfirmThrottleError(ApiTester $I)
    {
        // проверка валидации
        $I->sendPOST('/reset/phone-confirm', [
            'phone' => $this->object['phone'],
            'code'  => 123123
        ]);
        d($I->grabResponse());
        $I->seeResponseCodeIs(400);
    }

    public function actionPhoneConfirm(ApiTester $I)
    {
        // проверка валидации
        $I->sendPOST('/reset/phone-confirm', [
            'phone' => $this->object['phone'],
            'code'  => $this->object['sms_code']
        ]);
        d($I->grabResponse());
        $I->seeResponseCodeIs(201);

    }

    public function actionPhoneConfirmErrorToRepeatRequest(ApiTester $I)
    {
        // проверка валидации
        $I->sendPOST('/reset/phone-confirm', [
            'phone' => $this->object['phone'],
            'code'  => $this->object['sms_code']
        ]);
        d($I->grabResponse());
        $I->seeResponseCodeIs(400);
    }


}

<?php

namespace api\tests\api;

use api\tests\ApiTester;
use common\entities\user\User;
use common\fixtures\UserFixture;
use function GuzzleHttp\Promise\all;
use Yii;
use yii\helpers\ArrayHelper;

class EmailCest
{
    public $object;

    public function __construct()
    {
        $this->object = require codecept_data_dir() . 'userData.php';
    }

    public function userCreate()
    {
        $user = User::create($this->object['phone']);
        $user->email = $this->object['email'];
        $user->save();
    }

    public function actionSendRequestValidationError(ApiTester $I)
    {
        // проверка валидации
        $I->sendPOST('/reset/email');
        d($I->grabResponse());
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    public function actionSendRequest(ApiTester $I)
    {
        // проверка валидации
        $I->sendPOST('/reset/email', [
            'email' => $this->object['email']
        ]);
        d($I->grabResponse());
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        //		$token = $I->grabDataFromResponseByJsonPath('$.data.token[0]');
    }
    //
    //
    public function actionTokenError(ApiTester $I)
    {
        // проверка валидации
        $I->sendGET('/reset/email-confirm', [
            'token' => '',
        ]);
        d($I->grabResponse());
        $I->seeResponseCodeIs(400);
    }

    //
    public function actionTokenInvalidConfirm(ApiTester $I)
    {
        // проверка валидации
        $I->sendGET('/reset/email-confirm', [
            'token' => '123asdasd',
        ]);
        d($I->grabResponse());
        $I->seeResponseCodeIs(400);
    }

    public function success(ApiTester $I)
    {
        $user = User::findByPhone($this->object['phone']);
        $token = $user->password_reset_token;
        d($token);
        // проверка валидации
        $I->sendGET('/reset/email-confirm', [
            'token' => $token,
        ]);
        d($I->grabResponse());
        //		$I->seeResponseCodeIs(200);
    }


}

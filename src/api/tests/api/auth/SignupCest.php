<?php

namespace api\tests\api\auth;

use api\tests\ApiTester;
use api\tests\ClearDb;
use common\entities\user\User;
use common\fixtures\UserFixture;
use GuzzleHttp\Promise\all;
use Yii;
use yii\helpers\ArrayHelper;

class SignupCest
{
    public $object;

    /**
     * SignupAndLoginCest constructor.
     *
     * @param $object
     */
    public function __construct()
    {
        $this->object = require codecept_data_dir() . 'userData.php';
    }


    public function _fixtures(): array
    {
        return [
            //            'user' => [
            //                'class' => UserFixture::class,
            //                'dataFile' => codecept_data_dir() . 'user.php'
            //            ]
        ];
    }

    /**
     * @param ApiTester $I
     */
    public function actionSignupValidation(ApiTester $I)
    {
        // проверка валидации
        $I->sendPOST('/auth/signup');
//        d($I->grabResponse());
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function actionSignup(ApiTester $I)
    {
        $I->sendPOST('/auth/signup', [
            'phone' => $this->object['phone']
        ]);
//        d($I->grabResponse());
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function actionSignupRepeat(ApiTester $I)
    {
        $I->sendPOST('/auth/signup', [
            'phone' => $this->object['phone']
        ]);
//        d($I->grabResponse());
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function actionSmsCodeConfirmError(ApiTester $I)
    {
        $I->sendPOST('/auth/sms-code-confirm', [
            'phone' => $this->object['phone'],
            'code'  => 123123
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        d($I->grabResponse());
    }

    public function actionSmsCodeConfirm(ApiTester $I)
    {
        $I->sendPOST('/auth/sms-code-confirm', [
            'phone' => $this->object['phone'],
            'code'  => $this->object['sms_code']
        ]);
        $response = $I->grabResponse();
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
//        d($response);
        $this->object['token'] = $I->grabDataFromResponseByJsonPath("$.data.token")[0];
    }

    public function actionSmsCodeSetPassword(ApiTester $I)
    {
        $I->sendPOST('/auth/sms-code-set-password', [
            'phone'    => $this->object['phone'],
            'token'    => $this->object['token'],
            'password' => $this->object['password']
        ]);
        $response = $I->grabResponse();
        d($response);
    }


}

<?php

namespace api\tests\api\auth;

use api\tests\ApiTester;
use common\entities\user\User;
use common\fixtures\UserFixture;
use GuzzleHttp\Promise\all;
use Yii;
use yii\helpers\ArrayHelper;

class LoginCest
{
    public $object;


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

    public function actionLogin(ApiTester $I)
    {
        $I->sendPOST('/auth/login', [
            'phone'    => $this->object['phone'],
            'password' => $this->object['password']
        ]);
        $response = $I->grabResponse();
        d($response);
        $this->object['token'] = $I->grabDataFromResponseByJsonPath('$.data.token')[0];
    }

    public function actionUserInfo(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->object['token']);
        $I->sendPOST('/user/info');
        $response = $I->grabResponse();
        d($response);
    }


}

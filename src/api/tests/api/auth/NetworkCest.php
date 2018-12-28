<?php

namespace api\tests\api\auth;

use api\tests\ApiTester;
use common\entities\user\User;
use common\fixtures\UserFixture;
use function GuzzleHttp\Promise\all;
use Yii;
use yii\helpers\ArrayHelper;

class NetworkCest
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


    /**
     * @param ApiTester $I
     */
    public function actionNetworkVk(ApiTester $I)
    {
        // проверка валидации
        $I->sendGET('/auth/network?authclient=vkontakte');
        d($I->grabResponse());
        //		$I->seeResponseCodeIs(302);

    }


}

<?php

namespace api\tests;

use api\tests\_generated\ApiTesterActions;
use api\tests\_generated\FunctionalTesterActions;
use common\dictionaries\Role;
use common\entities\user\User;
use Faker\Factory;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use ApiTesterActions, TestHelpers;

    /**
     * Define custom actions here
     */
    /**
     * @param mixed $var
     *
     * @return User
     */
    public function login($var = [])
    {
        $user = $var instanceof User ? $var : $this->getFactory()->create(User::class, $var);
        $this->amBearerAuthenticated($user->authKey);

        return $user;
    }


    public function generatePhone(): string
    {
        return '+7707123' . random_int(1111, 9999);
    }

    public function signip()
    {
        $user = [
            'phone'    => $this->generatePhone(),
            'code'     => 123456,
            'password' => 'A123456'
        ];

        $this->wantTo('signup ' . $user['phone']);

        $this->sendPOST('/auth/signup', [
            'phone' => $user['phone']
        ]);

        $this->wantTo('signup configrm code ' . $user['phone']);

        $this->sendPOST('/auth/sms-code-confirm', [
            'phone' => $user['phone'],
            'code'  => $user['code']
        ]);

        $user['token'] = $this->grabDataFromResponseByJsonPath("$.data.token")[0];

        $this->wantTo('set password ' . $user['token']);

        $this->sendPOST('/auth/sms-code-set-password', [
            'phone'    => $user['phone'],
            'token'    => $user['token'],
            'password' => $user['password']
        ]);

        $this->amBearerAuthenticated($user['token']);

        return $user;
    }

    public function setRole($role)
    {
        $this->sendPOST('/user/set-role', [
            'role' => $role,
        ]);
    }

    public function setProfile($role)
    {
        $data = $this->createProfileData($role);
        $this->sendPOST('/profile', $data);

//        $response = $this->grabResponse();
//        d($response);

        return $data;
    }

    public function createProfileData($role)
    {
        $faker = Factory::create();

        $temp = [
            "first_name"  => $faker->firstName,
            "last_name"   => $faker->lastName,
            "email"       => $faker->email,
            "username"    => $faker->userName,
            "country_id"  => "4",
            "city_id"     => "183",
            "region_id"   => "1700503",
            "description" => $faker->text(200),
            'photo_id' => $this->sendPhoto()['id']
        ];


        if ($role == Role::R_ORGANIZER)
        {
            $temp = array_merge($temp, [
                "organizer_name"                => $faker->text(50),
                "organizer_legal_name"          => $faker->text(50),
                "organizer_address"             => $faker->address,
                "organizer_address_index"       => $faker->numberBetween(0, 999999),
                "organizer_legal_address"       => $faker->address,
                "organizer_legal_address_index" => $faker->numberBetween(0, 999999),
            ]);
        }

        return $temp;
    }

    public function sendPhoto($file = null)
    {
        $file = ($file ?? codecept_data_dir('img.jpg'));

        $this->sendPOST('/photo/upload', [], [
            'file' => $file
        ]);

//        $response = $this->grabResponse();
//        d($response);

        $file = $this->grabDataFromResponseByJsonPath("$.data.file")[0];

//        d($file);

        return $file;
    }


    public function checkLogin($url)
    {
        $this->sendPOST($url);
        $this->seeResponseCodeIs(401);
    }

    public function seeAccessDenied($url)
    {
        $this->sendPOST($url);
        $this->seeResponseCodeIs(403);
    }

    public function assignRoles($user, $role)
    {
        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole($role);
        $auth->assign($authorRole, $user->getId());
    }


}

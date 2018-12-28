<?php

use lib\services\user\UserProfileService;
use yii\db\Migration;

/**
 * Class m180518_062137_init_admin_and_test_user
 */
class m180518_062137_init_admin_and_test_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = (new \lib\services\auth\SignupService())->signup('+77473929816');
        $user->setSmsConfirmTrue();
        $user->setPassword('123456');
        $user->save();
        (new \lib\services\RoleManager())->assignRole($user->id, \common\entities\user\User::R_ADMIN);

	    $user = (new \lib\services\auth\SignupService())->signup('+77473929817');
	    $user->setSmsConfirmTrue();
	    $user->setPassword('123456');
	    $user->save();
	    (new \lib\services\RoleManager())->assignRole($user->id, \common\entities\user\User::R_RACER);

	    $user = (new \lib\services\auth\SignupService())->signup('+77473929818');
	    $user->setSmsConfirmTrue();
	    $user->setPassword('123456');
	    $user->save();
	    (new \lib\services\RoleManager())->assignRole($user->id, \common\entities\user\User::R_GAPER);

	    $user = (new \lib\services\auth\SignupService())->signup('+77473929819');
	    $user->setSmsConfirmTrue();
	    $user->setPassword('123456');
	    $user->save();
	    (new \lib\services\RoleManager())->assignRole($user->id, \common\entities\user\User::R_JOURNALIST);

	    $user = (new \lib\services\auth\SignupService())->signup('+77473929820');
	    $user->setSmsConfirmTrue();
	    $user->setPassword('123456');
	    $user->save();
	    (new \lib\services\RoleManager())->assignRole($user->id, \common\entities\user\User::R_ORGANIZER);

//        $service = new UserProfileService();
//        $form = new \api\forms\user\profile\ProfileForm([
//            'username'    => 'test_username',
//            'email'       => 'email@email.kz',
//
//            'first_name'  => 'Test FN',
//            'last_name'   => 'Test LN',
//            'country_id'  => 4,
//            'city_id'     => 183,
//            'region_id'   => 1700503,
//            'description' => 'description description description',
//        ]);
//        $form->role = $user->getFirstRoleName();
//        $user = $service->createOrUpdateUserProfile($user, $form);
//
//        \lib\services\team\TeamService::createTeam($user, 'test team');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180518_062137_init_admin_and_test_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180518_062137_init_admin_and_test_user cannot be reverted.\n";

        return false;
    }
    */
}

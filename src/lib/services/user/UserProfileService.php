<?php

namespace lib\services\user;


use api\exceptions\Http400Exception;
use api\forms\user\profile\ProfileForm;
use common\entities\user\User;
use common\entities\user\UserProfile;
use lib\helpers\Response;
use lib\helpers\UserHelper;
use SebastianBergmann\Timer\RuntimeException;
use yii\helpers\ArrayHelper;

class UserProfileService {

	public function checkIfProfileNotEmpty(User $user, $role = User::R_RACER) {
		if ( ! $user->profile) {
			throw new Http400Exception('Profile is empty');
		}
	}

	public function createOrUpdateUserProfile(User $user, ProfileForm $form)
	{
		$profile = $user->profile;
		$role    = $form->role;

		$fieldsToBd = RoleService::formattingFormAttributes($form, $role);

		if(!empty($fieldsToBd['email']))
		    $user->email    = ArrayHelper::remove($fieldsToBd, 'email');

        if(!empty($fieldsToBd['username']))
            $user->username    = ArrayHelper::remove($fieldsToBd, 'username');

		if (!$profile) {
			$profile          = new UserProfile($fieldsToBd);
			$profile->user_id = $user->id;
		}

        $profile->setAttributes($fieldsToBd);

		if ( ! $user->save()) {
			throw new RuntimeException('User save error');
		}

		if ( ! $profile->save()) {
			throw new RuntimeException('User profile save error' . \GuzzleHttp\json_encode($profile->getErrors()));
		}


		return $user;
	}

}

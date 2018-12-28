<?php

namespace lib\helpers;

use common\entities\user\User;
use common\entities\user\UserProfile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper {
	public static function statusList(): array {
		return [
			User::STATUS_DELETED => 'Deleted',
			User::STATUS_ACTIVE  => 'Active',
		];
	}

	public static function statusName($status): string {
		return ArrayHelper::getValue(self::statusList(), $status);
	}

	public static function statusLabel($status): string {
		switch ($status) {
			case User::STATUS_DELETED:
				$class = 'label label-danger';
				break;
			case User::STATUS_ACTIVE:
				$class = 'label label-success';
				break;
			default:
				$class = 'label label-default';
		}

		return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
			'class' => $class,
		]);
	}

	public static function serializeUser(User $user): array {
		return [
			'id'          => $user->id,
			'username'    => $user->username,
			'email'       => $user->email,
			'phone'       => $user->phone,
			'sms_confirm' => $user->sms_confirm,
			'online'      => $user->online,
			'last_online' => $user->last_online,
			'date'        => [
				'created' => DateHelper::formatApi($user->created_at),
				'updated' => DateHelper::formatApi($user->updated_at),
			],
			'status'      => [
				'code' => $user->status,
				'name' => UserHelper::statusName($user->status),
			],
			'profile'     => UserProfile::serializeItem($user->profile),
			'role'        => $user->getFirstRoleName()
		];
	}

	public static function serializeProfile($user) {
		return ArrayHelper::merge(
			self::serializeUser($user),
			[]
//            UserProfile::serializeItem($user->profile)
		);
	}

	/**
	 * @param null $id
	 *
	 * @return null|\yii\web\IdentityInterface|static
	 * @throws \api\exceptions\NotFoundException
	 */
	public static function byIdOrAuth($id = null) {
		return $id
			? User::findByIdOrFail($id)
			: User::authUser();
	}
}

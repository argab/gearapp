<?php

namespace lib\services\user;


use common\entities\user\User;
use yii\base\Model;

class RoleService {

	public static function getFieldsByRole($role) {

		if ($role == User::R_RACER) {
			return [
				'first_name',
				'last_name',
				'email',
				'username',
				'country_id',
				'city_id',
				'region_id',
				'team_id',
				"photo_id",
				"description"
			];
		}

		if ($role == User::R_GAPER) {
			return [
				'first_name',
				'last_name',
				'email',
				'username',
				'country_id',
				'city_id',
				'region_id',
				"photo_id",
				"description"
			];
		}


		if ($role == User::R_ORGANIZER) {
			return [
				'first_name',
				'last_name',
				'email',
				'country_id',
				'city_id',
				"region_id",
				"photo_id",
				"description",
				"organizer_name",
				"organizer_legal_name",
				"organizer_address",
				'organizer_address_index',
				"organizer_legal_address",
				'organizer_legal_address_index',
			];
		}


		if ($role == User::R_JOURNALIST) {
			return [
				'first_name',
				'last_name',
				'email',
				"organizer_name",
				"photo_id",
				"description",
				'country_id',
				'city_id',
				"region_id",
			];
		}

	}

	/**
	 * @param Model $form
	 * @param $role
	 *
	 * @return array
	 */
	public static function formattingFormAttributes($form, $role)
	{
		$fields = array_intersect_key(
			$form->getAttributes(),
			array_flip(RoleService::getFieldsByRole($role))
		);
		$fields = array_filter($fields);

		return $fields;
	}

	public static function formattingFormAttributesToUser($form, $role)
	{

		$fields = array_intersect_key(
			$form->getAttributes(),
			array_flip(RoleService::getFieldsByRole($role))
		);
		$fields = array_filter($fields);

		return $fields;
	}



}
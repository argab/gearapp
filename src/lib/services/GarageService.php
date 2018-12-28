<?php

namespace lib\services;

use api\exceptions\Http400Exception;
use common\entities\car\Car;
use common\entities\car\Garage;
use common\entities\team\Team;
use common\entities\user\User;
use yii\helpers\ArrayHelper;

class GarageService {


	public static function createByTeam(Team $team, $car): void {
		$garage = $team->garage;

		if (GarageService::checkIfCarInGarage($car->id, $garage)) {
			throw new Http400Exception('Машина уже в гараже');
		}

		$team->link('garage', $car);

	}

	public static function removeByTeam(Team $team, $car) {

		if (!GarageService::checkIfCarInGarage($car->id, $team->garage)) {
			throw new Http400Exception('Машина нет в гараже');
		}

		$team->unlink('garage', $car);

	}

	public static function createByUser(User $user, $car): void {

		if (GarageService::checkIfCarInGarage($car->id, $user->garage)) {
			throw new Http400Exception('Машина уже в гараже');
		}

		$user->link('garage', $car);
	}

	public static function removeByUser(User $user, $car): void {

		if (!GarageService::checkIfCarInGarage($car->id, $user->garage)) {
			throw new Http400Exception('Машина нет в гараже');
		}

		$user->unlink('garage', $car);

	}


	public static function checkIfCarInGarage($car_id, $garage) {
		$ids = ArrayHelper::getColumn($garage, 'id');

		return in_array($car_id, $ids);
	}


	public static function getCarById($garage, $car_id): Car {
		foreach ($garage as $item) {
			if ($item->id == $car_id) {
				return $item;
			}
		}

		throw new Http400Exception('Car not found in garage');
	}


	public static function deleteCarFromGarage($car_id, $user_id) {
		$result = Garage::find()->where(['car_id' => $car_id])
		                ->where(['user_id' => $user_id])
		                ->one();

		return $result->deleteOrFail();
	}

}

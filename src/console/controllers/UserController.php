<?php

namespace console\controllers;

use lib\services\RoleManager;
use common\entities\user\User;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

/**
 * Interactive console roles manager
 */
class UserController extends Controller
{
	/**
	 * @var RoleManager
	 */
	private $manager;


	public function __construct($id, $module, RoleManager $manager, $config = [])
	{
		parent::__construct($id, $module, $config);
		$this->manager = $manager;
	}

	/**
	 * @throws \Exception
	 * @throws \yii\base\Exception
	 */
	public function actionResetPassword()
	{
		$phone = $this->prompt('phone:', ['required' => true]);
		$user = User::findByPhone($phone);
		if(!$user)
			throw new \Exception('User is not found');

		$password = $this->prompt('пароль');
		$password2 = $this->prompt('пароль еще раз');

		if($password != $password2)
			throw new \Exception('Пароли не совпадают');

		if($this->confirm('Установить пароль: ' . $password)){
			$user->setPassword($password);
			if(!$user->save())
				throw new \Exception('Save error');

			$this->stdout('Done!' . PHP_EOL);
		}
    }


	public function actionRoleAssign()
	{
		$phone = $this->prompt('phone:', ['required' => true]);
		$user = User::findByPhone($phone);
		if(!$user)
			throw new \Exception('User is not found');


		$role = $this->select('Role:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
		$this->manager->assignRole($user->id, $role);
		$this->stdout('Done!' . PHP_EOL);
    }

}
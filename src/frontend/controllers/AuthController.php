<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

use common\forms\auth\LoginForm;
use common\forms\auth\SignupForm;
use lib\services\auth\SignupService;
use lib\services\sms\ConfirmSmsService;

class AuthController extends Controller {

	/**
	 * Logs in a user.
	 *
	 * @return mixed
	 */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			$model->password = '';

			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Logs out the current user.
	 *
	 * @return mixed
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Signs user up.
	 *
	 * @return mixed
	 */
	public function actionSignup()
	{
		$form = new SignupForm();

		if ($form->load(Yii::$app->request->post()) && $form->validate()) {
			if ($user = (new SignupService())->signup($form)) {

				try{
					(new ConfirmSmsService($form->phone))->sendConfirmSms($user);
					Yii::$app->session->setFlash('success', 'New password saved.');

				}catch(\Exception $e){

				}



				if (Yii::$app->getUser()->login($user)) {
					return $this->goHome();
				}
			}
		}

		return $this->render('signup', [
			'model' => $form,
		]);
	}


}
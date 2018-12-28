<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\traits\TAdminController;

use common\entities\user\User;
use common\forms\auth\LoginForm;
use lib\services\auth\SignupService;

class AdminController extends Controller
{
    use TAdminController;

    public function init()
    {
        if ( ! Yii::$app->user->isGuest && Yii::$app->user->can(User::R_ADMIN))
        {
            is_dir(Yii::getAlias('@admin/uploads')) or mkdir(Yii::getAlias('@admin/uploads'));
        }
    }

    protected function _accessRules()
    {
        return [
            [
                'allow'   => true,
                'roles'   => ['?'],
                'actions' => ['login', 'logout', 'signup']
            ],
            [
                'allow'   => true,
                'roles'   => [User::R_ADMIN],
                'actions' => ['index']
            ],
        ];
    }

    public function actionError()
    {
        return $this->render('error');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionLogin()
    {
        if ( ! Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
            return responseJson([
                'login_status' => true,
            ]);
        }
        else
        {
            $model->password = '';

            if (isAjax(false))
            {
                return responseJson([
                    'login_status' => ! ! $model->getErrors(),
                    'errors'       => $model->getErrors()
                ]);
            }

            return $this->getView()->render('login', [
                'model' => $model,
            ], $this);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}

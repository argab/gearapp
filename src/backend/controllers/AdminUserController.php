<?php

namespace backend\controllers;

use backend\traits\TAdminController;
use Yii;
use lib\base\BaseController;
use yii\filters\AccessRule;

use backend\models\AdminUser;
use common\entities\user\User;

class AdminUserController extends BaseController
{
    use TAdminController;

    const GETTER_USER = 'user';

    protected $baseGetter = self::GETTER_USER;

    protected $modelName = AdminUser::class;

    protected $modelTable = [
        self::GETTER_USER => AdminUser::TABLE,
    ];

    protected $getters = [
        self::GETTER_USER => 'findUser',
    ];

    protected $views = [
        self::GETTER_USER => [
            'index'  => 'adm_users',
            'create' => 'adm_users_create',
            'update' => 'adm_users_create',
            'view'   => 'adm_users_view',
        ],
    ];

    protected function _accessRules()
    {
        return [[
            'matchCallback' => function(AccessRule $rule, $action)
            {
                if ($action->id === 'view')

                    return true;

                $id = (int) ($_REQUEST['id'] ?? null);

                $uid = (int) Yii::$app->user->getId();

                if ($id && (Yii::$app->request->isPost || $action->id === 'delete'))
                {
                    if (false == Yii::$app->user->can(User::R_ADMIN))

                        return false;

                    if ($id !== $uid && $userRoles = (array) $this->roleManager->getRolesAll($id, true))
                    {
                        if (in_array(User::R_ADMIN, $userRoles) && $id !== $uid)

                            return false;
                    }

                    if ($roles = (array) request_post('roles'))
                    {
                        if ($id === $uid && $roles[0] !== $rule->roles[0])

                            return false;
                    }
                }

                return true;
            }
        ]];
    }

    public function afterAction($action, $result)
    {
        if ($action->id === 'update' && $roles = (array) request_post('roles'))
        {
            $userRoles = (array) $this->roleManager->getRolesAll($this->getModel()->id, true);

            foreach ($this->roleManager->manager()->getRoles() as $role)
            {
                if (in_array($role->name, $roles) && ! in_array($role->name, $userRoles))
                {
                    $assign = $this->roleManager->assignRole($this->getModel()->id, $role->name);

                    if (false == $assign)
                    {
                        $this->errors = ['Роль уже присвоена данному пользователю.'];

                        return parent::afterAction($action, $result);
                    }
                }
            }
        }

        return parent::afterAction($action, $result);
    }

}

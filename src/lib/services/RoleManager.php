<?php

namespace lib\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\ManagerInterface;
use common\entities\user\User;

class RoleManager
{
    private $manager;

    public function __construct(ManagerInterface $manager = null)
    {
        $this->manager = $manager ?: Yii::$app->getAuthManager();
    }

    public function manager()
    {
        return $this->manager;
    }

    public function assignRole($userId, $name): bool
    {
        if ( ! $role = $this->manager->getRole($name))
        {
            throw new \DomainException('Role "' . $name . '" does not exist.');
        }

        $this->manager->revokeAll($userId);

        try
        {
            $this->manager->assign($role, $userId);
        }
        catch (\Exception $e)
        {
            return false;
        }

        return true;
    }

    public function getRolesAll($user_id = null, $flip = false)
    {
        $roles = $user_id ? $this->manager->getRolesByUser($user_id) : $this->manager->getRoles();

        $output = ArrayHelper::map($roles, 'name', 'description');

        return $flip ? array_flip($output) : $output;
    }

    public function getRoles()
    {
        $allRoles = $this->getRolesAll();

        ArrayHelper::remove($allRoles, 'admin');

        $allRoles = $this->sortRoles($allRoles);

        return $allRoles;
    }

    public function getRoleNames($user_id = null)
    {
        if ($roles = $this->getRolesAll($user_id))
        {
            foreach ($roles as $k => $role)
            {
                $roles[$k] = User::ROLES[$k];
            }
        }

        return $roles;
    }

    public function sortRoles($roles)
    {
        $sort = [
            'gaper',
            'organizer',
            'racer',
            'journalist',
        ];

        return array_merge(array_flip($sort), $roles);
    }

}
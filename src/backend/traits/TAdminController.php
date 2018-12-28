<?php

namespace backend\traits;

use Yii;
use common\entities\user\User;
use backend\traits\TAdminAccessControl;
use lib\services\RoleManager;

trait TAdminController
{
    use TAdminAccessControl;

    private $roleManager;

    /**
     * constructor.
     *
     * @param $id
     * @param $module
     * @param RoleManager $roleManager
     * @param array $config
     */
    public function __construct($id, $module, RoleManager $roleManager, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->roleManager = $roleManager;

        $this->setBehavior();
    }
}

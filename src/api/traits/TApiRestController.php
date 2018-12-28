<?php

namespace api\traits;

use common\traits\TBehavior;
use lib\services\RoleManager;
use lib\helpers\Response;
use yii\web\Request;

trait TApiRestController
{
    use TBehavior;

    private $roleManager;

    private $response;
    
    private $request;

    /**
     * constructor.
     *
     * @param $id
     * @param $module
     * @param RoleManager $roleManager
     * @param Response $response
     * @param array $config
     */
    public function __construct($id, $module, RoleManager $roleManager, Response $response, Request $request, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->roleManager = $roleManager;

        $this->response = $response;

        $this->request = $request;

        $this->setBehavior();
    }
}

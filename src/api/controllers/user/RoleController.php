<?php

namespace api\controllers\user;

use lib\helpers\Response;
use lib\services\RoleManager;
use api\traits\TApiRestController;
use yii\rest\Controller;

class RoleController extends Controller
{

    use TApiRestController;

    public function _behaviorConfig()
    {
        return [
        ];
    }

    public function _behaviors()
    {
        return [
        ];
    }

    public function actionIndex()
    {
        $roles = $this->roleManager->getRoles();

        $temp = [];
        foreach ($roles as $k => $v)
        {
            $temp2 = [];
            $temp2['code'] = $k;
            $temp2['description'] = $v;
            $temp[] = $temp2;
        }

        return Response::responseItems($temp);
    }


}
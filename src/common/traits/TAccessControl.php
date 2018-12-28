<?php

namespace common\traits;

use yii\filters\AccessControl;
use common\traits\TBehavior;

trait TAccessControl
{
    use TBehavior;

    protected function _behaviorConfig():array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
            ]
        ];
    }

    protected function _behaviors():array
    {
        return [
            'access' => [
                'rules' => $this->_accessRules()
            ]
        ];
    }

    /**
     * [
            'allow'         => true,
            'roles'         => [],
            'matchCallback' => function(\yii\filters\AccessRule $rule, $action){}
       ]
     *
     * @return array
     */
    abstract function _accessRules(): array;

}

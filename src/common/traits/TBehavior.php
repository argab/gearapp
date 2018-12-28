<?php

namespace common\traits;

use yii\helpers\ArrayHelper;

trait TBehavior
{
    private $_behaviorConfig = [];

//    function _behaviors(): array;
//    function _behaviorConfig(): array;

    protected function setBehavior()
    {
        $this->_behaviorConfig = $this->_behaviorConfig();

	    if (method_exists($this, '_behaviors') && $behaviors = $this->_behaviors()) {
		    $this->_behaviorConfig = ArrayHelper::merge($this->_behaviorConfig, $behaviors);
	    }
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), $this->_behaviorConfig);
    }

}

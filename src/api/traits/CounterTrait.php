<?php

namespace api\traits;


use common\entities\Counter;

trait CounterTrait
{
    public function setLike()
    {
        Counter::setLike($this);
        $this->updateCounters(['likes' => 1]);
    }

    public function setDislike()
    {
        Counter::setDislike($this);
        $this->updateCounters(['likes' => -1]);
    }

    public function addViews()
    {
        Counter::setView($this);
        $this->updateCounters(['views' => 1]);
    }

    public function deleteAllCountersByThisModel()
    {
        Counter::deleteAllByModel($this);
    }

}

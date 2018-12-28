<?php

namespace api\traits;

use common\traits\TBehavior;

trait TApiAuthGuest
{
    protected function _behaviorConfig(): array
    {
        return [];
    }

    protected function _behaviors() : array
    {
        return [];
    }
}

<?php

namespace api\traits;

use lib\filters\EmptyProfileFilter;
use lib\filters\EmptyRoleFilter;
use lib\filters\PhoneFilledFilter;

trait TApiProfileHttpAuth
{
    use TApiHttpAuth;

    protected function _behaviors(): array
    {
        return [
            PhoneFilledFilter::class,
            EmptyRoleFilter::class,
            EmptyProfileFilter::class,
        ];
    }
}

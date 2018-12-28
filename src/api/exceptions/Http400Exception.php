<?php

namespace api\exceptions;

use yii\web\HttpException;

class Http400Exception extends HttpException
{
    public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
    {
        parent::__construct(400, $message, $code, $previous);
    }
}
<?php

namespace api\exceptions;

use lib\helpers\Response;
use yii\web\HttpException;

class NotFoundException extends HttpException
{
    public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
    {
        Response::addFieldsToResponse([
            'type' => 'not_found'
        ]);

        parent::__construct(400, $message, $code, $previous);
    }
}
<?php

namespace api\exceptions;

use lib\helpers\Response;
use yii\web\HttpException;

class ValidationException extends HttpException
{
    public function __construct(string $field = null, string $message = null, int $code = 0, \Exception $previous = null)
    {
        Response::addFieldsToResponse([
            'field' => $field,
            'type'  => 'validation'
        ]);

        parent::__construct(400, $message, $code, $previous);
    }

}
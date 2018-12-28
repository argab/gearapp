<?php

namespace lib\helpers;


class ErrorHandler extends \yii\base\ErrorHandler
{


    /**
     * Renders the exception.
     *
     * @param \Exception $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        return Response::errorException($exception->getMessage());
    }
}
<?php

namespace common\base;

use api\exceptions\Http400Exception;
use api\exceptions\ValidationException;
use yii\base\Model;

class Assert extends \Webmozart\Assert\Assert
{
    public static function notFound($item, $message = null)
    {
        if (empty($item))
            throw new Http400Exception($message ?? 'Item not found');
    }

    public static function isRemoved($delete, $message = null)
    {
        if ( ! $delete)
            throw new Http400Exception($message ?? 'Delete error');
    }
    
    public static function hasPermission($can, $message = null)
    {
        if (!$can)
            throw new Http400Exception($message ?? 'You are not allowed to perform this action.', 403);
    }


    protected static function reportInvalidArgument($message)
    {
        throw new Http400Exception($message);
    }

    public static function hasFormError(Model $form)
    {
        if ($form->hasErrors())
        {
            foreach ($form->getErrors() as $k => $v)
            {
                $k = array_pop(explode('.', $k));
                throw new ValidationException($k, $v[0]);
            }
        }
    }

    public static function save($value, $message = null)
    {
        if ( ! $value)
            throw new Http400Exception($message ?? 'Save error');
    }

    public static function alreadyExist()
    {

    }

    public static function notExist($value, $message = null)
    {
        if (empty($value))
            throw new Http400Exception($message ?? 'Not exist');
    }

}

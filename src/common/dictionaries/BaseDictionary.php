<?php

namespace common\dictionaries;

use common\base\Assert;
use Yii;

abstract class BaseDictionary
{
    abstract public static function all(): array;
    
    public static function get($key): string
    {
        $all = static::all();

        Assert::keyExists($all, $key);

        return $all[$key];
    }
    
    public static function keys(): array
    {
        return array_keys(static::all());
    }

    public static function allToResponse()
    {
        $result = [];
        foreach (static::all() as $k => $v){
            $result[] = [
                'key' => $k,
                'value'=> $v
            ];
        }

        return $result;

    }
}

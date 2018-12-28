<?php

namespace lib\helpers;


use yii\helpers\Url;

class Links
{

    public static function get($urlArr): array
    {
        $result = [];

        if (empty($urlArr))
            return $result;

        foreach ($urlArr as $k => $v)
        {
            $result[$k] = ['href' => Url::to($v)];
        }

        return $result;
    }

}
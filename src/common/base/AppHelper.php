<?php
/**
 * Created by PhpStorm.
 * User: Alimzhan
 * Date: 20.05.2017
 * Time: 9:51
 */

namespace common\base;

use DateTime;
use Yii;
use yii\web\ForbiddenHttpException;

class AppHelper
{

    /**
     * @return string Get random alphanumeric char
     */
    public static function randomAlphanumChar(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        return $characters[mt_rand(0, strlen($characters) - 1)];
    }

    public static function authCheck($permissionName, $params = [], $allowCaching = true)
    {
        if (!Yii::$app->user->can($permissionName, $params, $allowCaching)) {
            throw new ForbiddenHttpException();
        }
    }

    public static function arrayReplace(array &$haystack, $needle, $replacement)
    {
        $key = array_search($needle, $haystack);
        $haystack[$key] = $replacement;
    }


    public static function customNumberFormat($n, $precision = 1)
    {
        if ($n < 1000) {
            // Anything less than a thousand
            $n_format = number_format($n);
        } else if ($n < 1000000) {
            // Anything less than a million
            $n_format = number_format($n / 1000, $precision) . 'K';
        } else if ($n < 1000000000) {
            // Anything less than a billion
            $n_format = number_format($n / 1000000, $precision) . 'M';
        } else {
            // At least a billion
            $n_format = number_format($n / 1000000000, $precision) . 'B';
        }

        return $n_format;
    }

    public static function normalizeTsquery(string $text)
    {
        return implode(":* & ", array_map(function ($text) {
                return "'{$text}'";
            }, explode(" ", trim($text)))) . ":*";
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


}

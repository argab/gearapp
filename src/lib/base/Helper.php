<?php

namespace lib\base;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\Html;

class Helper
{
    /**
     * Encrypt and decrypt
     * @author Nazmul Ahsan <n.mukto@gmail.com>
     * @link   http://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/
     *
     * @param string|array $string string to be encrypted/decrypted
     * @param string $action       what to do with this? e for encrypt, d for decrypt
     *
     * @return string
     */
    public static function crypt($string, $action = 'e')
    {
        // you may change these values to your own
        $secret_key = get_params('enc.openssl.key');
        $secret_iv = get_params('enc.openssl.secret_iv');

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if (is_array($string))
        {
            $string = json_encode($string, true);
        }

        if ($action == 'e')
        {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        }
        elseif ($action == 'd')
        {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    public static function formatPhone($phone)
    {
        preg_match('/^\s*(\+)?\s*(7|8)?\s*\(?\s*([0-9]{3})\s*\)?\s*([0-9]{3})\s*\-?\s*([0-9]{2})\s*\-?\s*([0-9]{2})\s*$/', $phone, $match);

        if (sizeof($match))

            return [
                sprintf('+7 %s %s %s%s', $match[3], $match[4], $match[5], $match[6]),
                sprintf('+7%s%s%s%s', $match[3], $match[4], $match[5], $match[6]),
                sprintf('+7 %s %s %s %s', $match[3], $match[4], $match[5], $match[6]),
                sprintf('+7 (%s) %s-%s-%s', $match[3], $match[4], $match[5], $match[6]),
                sprintf('+7(%s)%s-%s-%s', $match[3], $match[4], $match[5], $match[6]),
                sprintf('+7 %s %s-%s-%s', $match[3], $match[4], $match[5], $match[6]),
            ];

        return null;
    }
}

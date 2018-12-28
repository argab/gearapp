<?php

namespace lib\helpers;


use Yii;
use yii\base\Event;

class Response
{

    public static function successCreated($data)
    {
        return self::responseSuccess($data, 200);
    }

    public static function success($data)
    {
        return self::responseSuccess($data, 200);
    }

    public static function successMessage($message)
    {
        $temp = [
            'message' => $message
        ];

        return self::responseSuccess($temp, 200);
    }

    public static function responseSuccess($data, $code = null, $text = null)
    {
        $response = \Yii::$app->response;
        $response->setStatusCode($code, $text);
        $response->data = $data;

        return $response;
    }

    public static function responseError($data, $code = null, $text = null)
    {

        /** @var \yii\web\Response $response */
        $response = \Yii::$app->response;
        $response->setStatusCode($code, $text);
        $response->data = $data;

        return $response;
    }

    public static function responseItemsWithPagination($data, $code = 200)
    {
        return self::responseSuccess($data, $code);
    }

    public static function responseItems($data, $code = 200)
    {
        $temp = [
            'items' => $data
        ];

        return self::responseSuccess($temp, $code);
    }

    public static function responseItem($data, $code = 200)
    {
        $temp = [
            'item' => $data
        ];

        return self::responseSuccess($temp, $code);
    }

	/**
	 * Вешаем еще одно событие на ответ, и мерджим массивы
	 * @param array $fields
	 */
	public static function addFieldsToResponse(array $fields)
    {
	    \Yii::$app->response->on(\Yii::$app->response::EVENT_BEFORE_SEND, function (Event $event) use($fields) {
		    $response = $event->sender;
		    $response->data['data'] = array_merge($response->data['data'], $fields);
	    });
    }

    public static function count($count)
    {
        return self::success(['count' => $count]);
    }

}

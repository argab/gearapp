<?php

use yii\helpers\VarDumper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\db\Query;


function _truncate($w, $num, $end = '')
{
    return \yii\helpers\StringHelper::truncateWords($w, $num, $end);
}

function showQuery(Query $query = null)
{
    exit($query ? $query->createCommand()->getRawSql() : Yii::$app->db->createCommand()->rawSql);
}

function vdd(...$var)
{
    foreach (func_get_args() as $item)
    {
        echo '<hr>';
        echo \yii\helpers\VarDumper::dump($item, 10, true);
        echo '<hr>';
    }
}

function vd(...$var)
{
    if (sizeof(func_get_args()) > 1)
    {
        vdd($var);
    }
    else
    {
        echo '<hr>';
        echo \yii\helpers\VarDumper::dump(func_get_args()[0], 10, true);
        echo '<hr>';
    }

    exit;
}

function dd(...$var)
{
    foreach (func_get_args() as $item)
    {
        dump($item);
    }

	exit;
}

function d(...$var)
{
    sizeof(func_get_args()) > 1 ? dd($var) : dump(func_get_args()[0]);
}

function pp(...$var)
{
    foreach (func_get_args() as $item)
    {
        echo '<pre>';
        print_r($item);
        echo '</pre>';
    }
}

function p(...$var)
{
    if (sizeof(func_get_args()) > 1)
    {
        pp($var);
    }
    else
    {
        echo '<pre>';
        print_r(func_get_args()[0]);
        echo '</pre>';
    }

    exit;
}

function ts()
{
    Yii::$app->session->set('dump_microtime', microtime(true));
}

function te($exit = true)
{
    $mt = microtime(true) - Yii::$app->session->get('dump_microtime');

    $exit ? p($mt) : pp($mt);
}

function parse_number($str)
{
    $str = str_replace(',', '.', $str);
    $result = '';

    for ($i = 0, $n = strlen($str); $i < $n; $i++)
    {
        if (ctype_digit($str[$i]) || $str[$i] == '.' || $str[$i] == '-')
        {
            $result .= $str[$i];
        }
    }

    return $result;
}

function parse_decimal($str, $rnd = 2)
{
    return number_format(preg_replace('/[^\d\.]+/', "", str_replace(',', '.', $str)), $rnd, '.', '');
}

function parse_float($str, $lower = null, $upper = null)
{
    $n = floatval(parse_number($str));

    if (isset($lower) && $n < $lower)
    {
        $n = $lower;
    }
    if (isset($upper) && $n > $upper)
    {
        $n = $upper;
    }

    return $n;
}

function parse_int($str, $lower = null, $upper = null)
{
    $n = intval(parse_number($str));

    if (isset($lower) && $n < $lower)
    {
        $n = $lower;
    }
    if (isset($upper) && $n > $upper)
    {
        $n = $upper;
    }

    return $n;
}

function extension($filename)
{
    $pathinfo = pathinfo($filename);

    $extension = get_value($pathinfo, 'extension', '');

    return $extension;
}

function flash_get($key, $defaultValue = null, $delete = false)
{
    return Yii::$app->session->getFlash($key, $defaultValue, $delete);
}

function flash_set($key, $value = true, $removeAfterAccess = true)
{
    Yii::$app->session->setFlash($key, $value, $removeAfterAccess);
}

function request_post($name = null, $defaultValue = null)
{
    return Yii::$app->request->post($name, $defaultValue);
}

function request_get($name = null, $defaultValue = null)
{
    return Yii::$app->request->get($name, $defaultValue);
}

function get_session_id()
{
    Yii::$app->session->open();

    return Yii::$app->session->id;
}

function get_user_id()
{
    return is_guest() ? 0 : get_value(user(), 'id', 0);
}

function get_user_ip()
{
    return Yii::$app->request->getUserIP();
}

function is_guest()
{
    return Yii::$app->user->isGuest;
}

function startsWith($str, $prefix)
{
    return substr($str, 0, strlen($prefix)) == $prefix;
}

function get_columns($table, $prefix = '', $db = 'db')
{
    $columns = [];

    $sh = Yii::$app->{$db}->getSchema()->getTableSchema($table)->getColumnNames();

    foreach ($sh as $column)
    {
        if ($prefix)
        {
            if (startsWith($column, $prefix))

                $columns[] = $column;

            continue;
        }

        $columns[] = $column;
    }

    return $columns;
}

function get_map($array, $from, $to = null, $group = null)
{
    if ($to !== null)
    {
        return ArrayHelper::map($array, $from, $to, $group);
    }

    $result = [];

    foreach ($array as $key => $value)
    {
        $result[get_value($value, $from)] = $value;
    }

    return $result;
}

function get_map_multi($array, $from, $to = null)
{
    $result = [];

    foreach ($array as $key => $value)
    {
        if ($to != null)
        {
            $result[get_value($value, $from)][] = get_value($value, $to);
        }
        else
        {
            $result[get_value($value, $from)][] = $value;
        }
    }

    return $result;
}

function get_column($array, $name, $keepKeys = true)
{
    return ArrayHelper::getColumn($array, $name, $keepKeys);
}

function get_value($array, $key, $default = null)
{
    if (is_array($key))
    {
        foreach ($key as $item)
        {
            if ($val = ArrayHelper::getValue($array, $item))

                return $val;
        }

        return $default;
    }

    return ArrayHelper::getValue($array, $key, $default);
}

function get_params($key, $default = null)
{
    return ArrayHelper::getValue(Yii::$app->params, $key, $default);
}

function get_cache_ttl($cache_type)
{
    if (isset(get_params('cache.ttl')[$cache_type]))
    {
        return get_params('cache.ttl')[$cache_type];
    }
    else
    {
        return get_params('cache.ttl.default');
    }
}

function cache_get($key, $cachePrefix = null)
{
    if ( ! get_params('cache.on'))

        return false;

    if ($cachePrefix)

        Yii::$app->cache->setPrefix($cachePrefix);

    $px = $cachePrefix ? null : get_params('cache.prefix');

    return Yii::$app->cache->get($px . $key);
}

function cache_set($key, $value, $cache_type = 'default', $duration = 0, $cachePrefix = null, $dependency = null)
{
    if ( ! get_params('cache.on'))

        return false;

    if ($cachePrefix)

        Yii::$app->cache->setPrefix($cachePrefix);

    $duration = intval($duration) ?: get_cache_ttl($cache_type);

    if ($value instanceof SimpleXMLElement || is_array($value))
        // fix for trouble "Serialization of 'SimpleXMLElement' is not allowed"
        $value = json_decode(json_encode($value), 1);

    $px = $cachePrefix ? null : get_params('cache.prefix');

    return Yii::$app->cache->set(
        $px . $key,
        $value,
        $duration,
        $dependency
    );
}

function cache_delete($key, $cachePrefix = null)
{
    if ($cachePrefix)

        Yii::$app->cache->setPrefix($cachePrefix);

    $px = $cachePrefix ? null : get_params('cache.prefix');

    return Yii::$app->cache->delete($px . $key);
}

function current_url($with_params = true)
{
    return $with_params ? Yii::$app->request->url : Yii::$app->request->pathInfo;
}

function is_front()
{
    $controller = Yii::$app->controller;
    $default_controller = Yii::$app->defaultRoute;

    if ($controller === null)
    {
        return false;
    }

    return ($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction);
}

function url_home($scheme = false)
{
    return Url::home($scheme);
}

function url_to($url = '', $scheme = false)
{
    return Url::to($url, $scheme);
}

function get_url($url, $options = [], $request_timeout = 10, $connection_timeout = 15, $debug = false)
{
    $ch = curl_init();
    $options[CURLOPT_URL] = $url;
    $options[CURLOPT_RETURNTRANSFER] = true;
    $options[CURLOPT_HEADER] = false;
    $options[CURLOPT_FOLLOWLOCATION] = 1;

    $options[CURLOPT_SSL_VERIFYHOST] = false;
    $options[CURLOPT_SSL_VERIFYPEER] = false;
    $options[CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1;

    if ($request_timeout != 0)
    {
        $options[CURLOPT_TIMEOUT] = $request_timeout;
    }

    if ($connection_timeout != 0)
    {
        $options[CURLOPT_CONNECTTIMEOUT] = $connection_timeout;
    }

    curl_setopt_array($ch, $options);

    $res = curl_exec($ch);

    if ($res == false)
    {
        if ($debug)
        {
            curl_close($ch);

            return curl_error($ch);
        }
    }
    curl_close($ch);

    return $res;
}

/**
 * @return \yii\web\IdentityInterface|\common\models\User|null
 */
function user()
{
    return Yii::$app->user->identity ?? null;
}

function isLogged()
{
    return user() !== null;
}

function hasRole($name)
{
}

function isAdmin($withRule = null)
{
}

function isManager($withRule = null)
{
}

function isAdminOrManager($withRule = null)
{
}

function isUser($withRule = null)
{
}

function hasRule($rules)
{

}

function csrf()
{
    return Yii::$app->getRequest()->getCsrfToken();
}

function isAjax($autoexit = true)
{
    $is = Yii::$app->request->getIsAjax();

    if ( ! $is && $autoexit) exit;

    return $is;
}

function responseJson(array $output = [])
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    return $output;
}

function redirectBack($error = null, $success = null)
{
    if ($error or $success)

        Yii::$app->getSession()->setFlash($error ? 'mess_error' : 'mess_success', $error ?: $success);

    header('Location:' . Yii::$app->request->referrer);

    exit;
}

function isLocal()
{
    return in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '192.168.1.1']);
}

function fileTouch($src, $getTime = false)
{
    $tt = null;

    $path = getenv('DOCUMENT_ROOT') . '/' . ltrim($src, '/');

    if (file_exists($path))

        $tt = filemtime($path);

    return $getTime ? $tt : $src . '?v=' . $tt;
}

function append_timestamps(array & $files)
{
    foreach ($files as & $i)
    {
        $i = fileTouch($i);
    }
}

function br2nl($str)
{
    return str_ireplace(["<br />", "<br>", "<br/>"], "\r\n", $str);
}

function getClassName($object)
{
    return (new \ReflectionClass($object))->getShortName();
}

/**
 * @param null|string $param
 *
 * @return \yii\web\Controller|mixed
 */
function controller(string $param = null)
{
    if (($cn = Yii::$app->controller) && $param)

        return $cn->{$param} ?? (method_exists($cn, $param) ? $cn->{$param}() : null);

    return $cn;
}

function array_key_value_wrap($items)
{
	$item = [];
	foreach ($items as $k => $v)
	{
		$temp = [];
		$temp['key'] = $k;
		$temp['value'] = $v;
		$item[] = $temp;
	}

	return $item;
}
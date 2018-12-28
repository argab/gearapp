<?php

namespace lib\helpers;

use yii\redis\Cache;

class CustomRedisCache extends Cache
{
    protected $_prefix = null;

    public function setPrefix($name)
    {
        $this->_prefix = preg_replace('/[^a-z\d_\-]+/ui', '', $name) . '_';
    }

    public function buildKey($key)
    {
        if ( ! is_string($key))
        {
            return serialize($key);
        }

        $key = preg_replace('/^(' . $this->keyPrefix . ')?(.*?)$/ui', '$2', $key);

        if ( ! preg_match('/^[a-z\d_\-]+$/ui', $key))

            $key = md5($key);

        return rtrim($this->keyPrefix, '_') . '_' . $this->_prefix . $key;
    }

}

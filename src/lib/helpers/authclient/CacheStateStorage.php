<?php

namespace lib\helpers\authclient;


use Yii;
use yii\authclient\StateStorageInterface;
use yii\base\Component;
use yii\redis\Cache;

class CacheStateStorage extends Component implements StateStorageInterface
{

    /**
     * @var Cache
     */
    public $cache;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if ($this->cache === null)
        {
            if (Yii::$app->has('cache'))
            {
                $this->cache = Yii::$app->get('cache');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        if ($this->cache !== null)
        {
            $this->cache->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ($this->cache !== null)
        {
            return $this->cache->get($key);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        if ($this->cache !== null)
        {
            $this->cache->delete($key);
        }

        return true;
    }
}
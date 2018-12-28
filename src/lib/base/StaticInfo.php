<?php

namespace lib\base;

use yii\db\Query;
use yii\helpers\ArrayHelper;

class StaticInfo
{
    protected static $table = 'static_info';

    protected static $groups;

    public static function get($key = null, $group = null, $equalKey = true)
    {
        return static::getGroup($group, $key) ?: static::search($key, $group, $equalKey);
    }

    public static function getGroups()
    {
        if ($groups = (new Query)->from('static_info_groups')->all())
        {
            return ArrayHelper::map($groups, 'key', 'name');
        }

        return null;
    }

    protected static function search($key = null, $group = null, $equalKey = true)
    {
        $q = (new Query)->from(static::$table);

        $q->select(['group_key', 'key', 'value']);

        if ($group !== null)
        {
            $q->where(['group_key' => $group]);
        }
        if ($key !== null)
        {
            if ($equalKey)
            {
                $q->where(['key' => $key]);
            }
            else
            {
                $q->andFilterWhere(['like', 'key', $key]);
            }
        }

        $q->andWhere(['show' => 1]);

        $q->orderBy(['priority' => SORT_ASC]);

        if ($val = $q->all())
        {
            foreach ($val as $item)
            {
                static::$groups[$item['group_key']][$item['key']] = $item['value'];
            }
        }

        return static::getGroup($group, $key);
    }

    protected static function getGroup($group = null, $key = null)
    {
        if (isset(static::$groups[$group]))

            return $key ? (static::$groups[$group][$key] ?? null) : static::$groups[$group];

        if ($key && sizeof(static::$groups))
        {
            foreach (static::$groups as $k => $v)
            {
                if (isset(static::$groups[$k][$key]))
                {
                    return static::$groups[$k][$key];
                }
            }
        }

        return null;
    }

}

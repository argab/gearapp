<?php

namespace api\traits;

use api\exceptions\Http400Exception;
use api\exceptions\NotFoundException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Трейт для модефикайия моделей для работы с апи
 * Trait TApiModel
 * @package common\entities
 */
trait TApiModel
{

    /**
     * @param ActiveQuery $query
     * @param array $params
     * [
     * "phone" => "7"
     * "country_name" => ""
     * ]
     *
     * @return ActiveQuery
     */
    public static function andFilterWhereLikeByParams($query, array $params): ActiveQuery
    {
        $model = new $query->modelClass();
        $attributes = $model->searchFields();
        foreach ($params as $k => $v)
        {
            if (!empty($v) && in_array($k, $attributes))
                $query->andFilterWhere(['like', $k, $v . '%', false]);
        }

        return $query;
    }

	public static function andFilterWhereLikeAllByParams($query, array $params): ActiveQuery
	{
		$model = new $query->modelClass();
		$attributes = array_keys($model->searchFields());
		foreach ($params as $k => $v)
		{
			if (!empty($v) && in_array($k, $attributes))
				$query->andFilterWhere(['like', $k, $v]);
		}

		return $query;
	}

    /**
     * Можно переопределить метод и возращать свои поля
     * @return array
     */
    public function searchFields()
    {
        return array_keys($this->attributeLabels());
    }


    /**
     * Для вывода
     *
     * @param $items
     * @param array $params
     *
     * @return array
     */
    public static function serialize($items, $params = [])
    {
        if (is_array($items))
        {
            return array_map(function($item) use ($params){
                return static::serializeItem($item, $params);
            }, $items);
        }

        return static::serializeItem($items, $params);
    }

    /**
     * Для вывода еденичного результата,
     * переопредели, что выозращать другие значения
     * @param $item
     *
     * @return array
     */
    public static function serializeItem($item, $params = [])
    {
        $result = ArrayHelper::toArray($item);
//        if(in_array('full', $params))
    	return $result;
    }


    /**
     * Простой поиск с лайком param% в таком виде
     * @param $params
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function mySearch($params)
    {
        $items = self::find()->limit(10);
        $items = self::andFilterWhereLikeByParams($items, $params);
        return $items->all();
    }

	/**
	 * Ищет и возращает для ответа в json
	 *
	 * @param $params
	 *
	 * @param array $serializeParams
	 *
	 * @return array
	 */
    public static function searchWithSerialize($params, $serializeParams = [])
    {
        return static::serialize(
            static::mySearch($params),
            $serializeParams
        );
    }

    /**
     * Поиск по id
     * @param $id
     * @param string $id_name
     *
     * @return mixed
     */
    public static function findById($id, $id_name = 'id')
    {
    	return static::find()->where([$id_name => $id])->limit(1)->one();
    }


    /**
     * @param $id
     * @param string $id_name
     *
     * @return static $item;
     * @throws NotFoundException
     */
    public static function findByIdOrFail($id, $id_name = 'id')
    {
        $item = static::findById($id, $id_name);

        if(!$item)
            throw new NotFoundException(static::shorClass() . ' item by id not found');

        return $item;
    }

    /**
     * @return mixed
     */
    public static function shorClass()
    {
        return end(explode("\\", static::class));
    }



    /**
     * Обновление полей по id
     * @param $id
     * @param $data
     * @param string $id_name
     *
     * @return mixed
     */
    public static function updateById($id, $data,  $id_name = 'id')
	{
		return static::updateAll($data, ['=', $id_name, $id]);
	}


	/**
	 * @param $data
	 *
	 * @return static
	 */
	public static function createWithoutSave($data)
	{
		return new static($data);
	}

	/**
	 * @throws Http400Exception
	 */
	public function saveOrFail($runValidation = true, $attributeNames = null)
	{
		if(!$this->save($runValidation = true, $attributeNames = null))
			throw new Http400Exception(json_encode($this->getFirstErrors()));

		return $this;
	}

	/**
	 * @return $this
	 * @throws Http400Exception
	 * @throws \Exception
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteOrFail()
	{
		if(!$this->delete())
			throw new Http400Exception('Delete error');

		return $this;
	}


    /**
     * @param $arr
     *
     * @return mixed
     */
    public static function findByIdArr($arr)
    {
        return static::find()->where(['in', 'id',$arr])->all();
    }

}

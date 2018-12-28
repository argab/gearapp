<?php

namespace common\traits;

use Yii;
use yii\db\ActiveRecord;
use common\traits\TGridForm;

trait TModel
{
    use TGridForm;

    private static $_tableName;

    public function formName()
    {
        return '';
    }

    public function scenarios()
    {
        return $this->_scenarios();
    }

    private function _scenarios()
    {
        $scenarios = parent::scenarios();

        if ( ! defined('static::SCENARIO_CREATE') || ! defined('static::SCENARIO_UPDATE'))

            return $scenarios;

        $scenarios[static::SCENARIO_CREATE] = $scenarios[static::SCENARIO_UPDATE] = $this->attributes();

        return $scenarios;
    }

    public function setTable($name)
    {
        static::$_tableName = $name;

        return $this;
    }

    public static function tableName()
    {
        return static::$_tableName ?: (defined('static::TABLE') ? static::TABLE : null);
    }

    public static function getDb()
    {
        return Yii::$app->db;
    }

    public function getLastInsertId()
    {
        return static::getDb()->lastInsertID;
    }

    public function deleteItem($id)
    {
        $param = ['id' => $id];

        return static::getDB()->createCommand()->delete(static::tableName(), $param)->execute();
    }

}

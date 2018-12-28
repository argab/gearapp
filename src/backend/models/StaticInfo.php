<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;

use backend\models\StaticInfoGroup;

class StaticInfo extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $group_name, $add_group;

    public function formName()
    {
        return '';
    }

    public function beforeValidate()
    {
        $this->add_group = trim(Yii::$app->request->post('add_group'));

        return parent::beforeValidate();
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[static::SCENARIO_CREATE] = $scenarios[static::SCENARIO_UPDATE] = $this->attributes();

        return $scenarios;
    }

    public function beforeSave($insert)
    {
        $return = parent::beforeSave($insert);

        $this->priority = intval($this->priority);

        if ($this->add_group)
        {
            $model = new StaticInfoGroup;

            if ($model->load(['name' => $this->add_group], '') && $model->save())

                $this->group_key = $model->key;
        }

        return $return;
    }

    public function rules()
    {
        return [
            [['key', 'name', 'value'], 'filter', 'filter' => 'trim'],
            [
                'key',
                'filter',
                'filter' => function($value)
                {
                    return strtolower($value);
                }
            ],
            ['key', 'unique'],
            ['add_group', 'unique', 'targetClass' => StaticInfoGroup::class, 'targetAttribute' => 'name'],
            ['key', 'match', 'pattern' => '/^[a-z\d_\-]+$/', 'message' => 'Ключевое слово может содержать только англ. буквы, дефис, нижнее подчеркивание и цифры.'],
            ['group_key', 'exist', 'targetClass' => StaticInfoGroup::class, 'targetAttribute' => 'key'],
            [
                'group_key',
                'required',
                'when' => function($model)
                {
                    return empty($model->add_group);
                },
                'on'   => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            [['key', 'name', 'value', 'add_group'], 'string'],
            [['group_key', 'priority'], 'integer'],
            ['show', 'boolean'],
            [
                [
                    'id',
                ],
                'safe'
            ],
        ];
    }

    public static function tableName()
    {
        return 'static_info';
    }

    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'group_key'  => 'Группа',
            'add_group'  => 'Добавить группу',
            'group_name' => 'Группа',
            'key'        => 'Ключевое слово',
            'name'       => 'Название',
            'value'      => 'Значение',
            'show'       => 'Отображать в приложении',
            'priority'   => 'Приоритет',
        ];
    }

    public function findInfo()
    {
        return $this->find()
            ->select([self::tableName() . '.*', 'group_name' => 'g.name'])
            ->innerJoin('static_info_groups g', 'g.key=' . self::tableName() . '.group_key');
    }

    public function deleteItem($id)
    {
        return Yii::$app->db->createCommand(
            'DELETE FROM `' . self::tableName() . '` WHERE `id`=:id'
        )
            ->bindValue(':id', $id)
            ->execute();
    }

}

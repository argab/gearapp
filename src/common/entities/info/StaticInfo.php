<?php

namespace common\entities\info;

use Yii;

/**
 * This is the model class for table "static_info".
 *
 * @property int $id
 * @property string $group_key
 * @property string $key
 * @property string $name
 * @property string $value
 * @property int $show
 * @property int $priority
 *
 * @property StaticInfoGroups $groupKey
 */
class StaticInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'static_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_key', 'show', 'priority'], 'integer'],
            [['key', 'name', 'value'], 'required'],
            [['value'], 'string'],
            [['key', 'name'], 'string', 'max' => 255],
            [['key'], 'unique'],
            [['group_key'], 'exist', 'skipOnError' => true, 'targetClass' => StaticInfoGroups::className(), 'targetAttribute' => ['group_key' => 'key']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_key' => 'Group Key',
            'key' => 'Key',
            'name' => 'Name',
            'value' => 'Value',
            'show' => 'Show',
            'priority' => 'Priority',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupKey()
    {
        return $this->hasOne(StaticInfoGroups::className(), ['key' => 'group_key']);
    }
}

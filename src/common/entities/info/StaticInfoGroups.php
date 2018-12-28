<?php

namespace common\entities\info;

use Yii;

/**
 * This is the model class for table "static_info_groups".
 *
 * @property string $key
 * @property string $name
 *
 * @property StaticInfo[] $staticInfos
 */
class StaticInfoGroups extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'static_info_groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'key' => 'Key',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaticInfos()
    {
        return $this->hasMany(StaticInfo::className(), ['group_key' => 'key']);
    }
}

<?php

namespace common\entities\file;

use api\traits\TApiModel;
use common\entities\user\User;
use lib\services\file\FileService;
use Yii;

/**
 * This is the model class for table "photo".
 * @property int $id
 * @property int $user_id
 * @property string $hash
 * @property string $name
 * @property string $file_name
 * @property string $path
 * @property User $user
 */
class Files extends \yii\db\ActiveRecord
{
    use TApiModel;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['hash', 'name', 'file_name', 'path'], 'string', 'max' => 255],
            [['hash'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'user_id'   => 'User ID',
            'hash'      => 'Hash',
            'name'      => 'Name',
            'file_name' => 'File Name',
            'path'      => 'Path',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @param $hash
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByHash($hash)
    {
        return self::find()->where(['hash' => $hash])->one();
    }


    /**
     * @param self $item
     */
    public static function serializeItem($item)
    {
        if ( ! $item)
            return null;

        return FileService::serializeFile($item);
    }
}

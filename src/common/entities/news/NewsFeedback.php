<?php

namespace common\entities\news;

use api\traits\TApiModel;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "news_feedback".
 *
 * @property int $id
 * @property int $news_id
 * @property string $fio
 * @property string $communication_format
 * @property string $description_event
 * @property int $created_at
 * @property int $updated_at
 *
 * @property News $news
 */
class NewsFeedback extends \yii\db\ActiveRecord
{
    use TApiModel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_feedback';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'created_at', 'updated_at'], 'integer'],
            [['fio', 'communication_format', 'description_event'], 'string', 'max' => 255],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::class, 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'news_id' => 'News ID',
            'fio' => 'Fio',
            'communication_format' => 'Communication Format',
            'description_event' => 'Description Event',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::class, ['id' => 'news_id']);
    }
}

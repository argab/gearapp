<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use common\entities\user\User;
use yii\web\UploadedFile;
use common\traits\TModel;

use api\modules\article\entity\Articles;
use backend\models\ArticleCategory;

/**
 * This is the model class for table "articles".
 * @property integer $id         ;
 * @property integer $category_id;
 * @property integer $user_id    ;
 * @property string $meta_keys   ;
 * @property string $meta_content;
 * @property string $title       ;
 * @property string $slide_title ;
 * @property boolean $in_slider  ;
 * @property string $image       ;
 * @property string $banner      ;
 * @property string $short_desc  ;
 * @property string $content     ;
 * @property boolean $status     ;
 * @property string $created_at  ;
 * @property string $updated_at  ;
 */
class Article extends \yii\db\ActiveRecord
{
    use TModel;

    const TABLE = 'articles';

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $category, $user;

    public function rules()
    {
        return [
            [['title', 'category_id'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            ['category_id', 'exist', 'targetClass' => ArticleCategory::class, 'targetAttribute' => 'id'],
            ['category', 'string'],
            ['user', 'string'],
            ['title', 'string', 'max' => '255'],
            ['slide_title', 'string', 'max' => '100'],
            ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['meta_keys', 'string', 'max' => '255'],
            ['meta_content', 'string', 'max' => '500'],
            ['short_desc', 'string', 'max' => '1000'],
            ['content', 'string'],
            ['status', 'boolean', 'strict' => true],
            ['in_slider', 'boolean', 'strict' => true],
            [
                [
                    'id',
                    'category_id',
                    'user_id',
                    'created_at',
                    'updated_at',
                    'status',
                ],
                'safe'
            ],
            [['image', 'banner'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'category_id'  => 'Категория',
            'category'     => 'Категория',
            'user_id'      => 'Автор',
            'user'         => 'Автор',
            'status'       => 'Статус',
            'meta_keys'    => 'Мета ключи',
            'meta_content' => 'Мета описание',
            'in_slider'    => 'Добавить в слайдер',
            'title'        => 'Заголовок',
            'slide_title'  => 'Заголовок в слайдере',
            'image'        => 'Изображение',
            'image_thumb'  => 'Превью',
            'banner'       => 'Изображение в слайдере',
            'short_desc'   => 'Короткое описание',
            'content'      => 'Содержание публикации',
            'created_at'   => 'Дата создания',
            'updated_at'   => 'Дата изменения',
        ];
    }

    public function beforeSave($insert)
    {
        $return = parent::beforeSave($insert);

        $this->user_id = Yii::$app->user->getId();

        return $return;
    }

    public function getDefaults()
    {
        $this->status = Articles::STATUS_BLOCKED;

        return $this;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    public function findArticle($id = 0)
    {
        $q = $this->find()->alias('a');

        return $id ? $q->where(['a.id' => $id]) : $q;
    }

    public function getCategory()
    {
        return $this->hasOne(ArticleCategory::class, ['category_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'id']);
    }

}

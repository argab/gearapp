<?php

namespace api\forms\news;

use api\forms\GeoForm;
use api\forms\PhotoForm;
use api\forms\PhotosForm;
use common\base\CompositeForm;
use common\dictionaries\NewsType;
use Yii;

/**
 * @property GeoForm $geo
 * @property PhotoForm $photo
 * @property PhotosForm $photos
 */
class NewsForm extends CompositeForm
{
    public $title;
    public $description;
    public $tags;
    public $type;
    public $post_date;
    public $post_date_close;

    public function __construct(array $config = [])
    {
        $this->geo = new GeoForm();
        $this->photo = new PhotoForm();
        $this->photos = new PhotosForm();
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['type', 'title', 'description'], 'required'],
            [['title', 'description'], 'trim'],
            [['description'], 'string'],
            [['title', ], 'string', 'max' => 255],
            [['title', 'description'], 'string', 'max' => 255],
            ['tags', 'each', 'rule' => ['string']],
            ['type', 'in', 'range' => NewsType::keys()],
            [
                ['post_date', 'post_date_close'], 'datetime',
                'format' => 'php:Y-m-d H:i:s',
//                'min' => gmdate('Y-m-d H:i:s'),
//                'tooSmall' => Yii::t('app', '{attribute} must be no less than {min}.', ['min' => Yii::t('app', 'current time')])
            ],

        ];
    }

    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms()
    {
        return ['geo', 'photo', 'photos'];
    }

}

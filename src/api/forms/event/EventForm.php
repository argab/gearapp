<?php

namespace api\forms\event;

use api\forms\GeoForm;
use api\forms\PhotoForm;
use api\forms\PhotosForm;
use common\base\CompositeForm;
use common\dictionaries\EventType;

/**
 * @property GeoForm $geo
 * @property PhotoForm $photo
 * @property PhotosForm $photos
 */
class EventForm extends CompositeForm
{
    public $title;
    public $description;
    public $type;
    public $event_date_start;
    public $event_date_end;
    public $latitude;
    public $longitude;
    public $is_hide;
    
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
            [['type', 'title', 'description', 'event_date_start', 'event_date_end','latitude', 'longitude'], 'required'],
            [['title', 'description'], 'trim'],
            [['description'], 'string'],
            [['title',], 'string', 'max' => 255],
            [['title', 'description'], 'string', 'max' => 255],
            [['latitude', 'longitude'], 'match', 'pattern' => '#[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)#'],
            [['is_hide'], 'boolean'],
            ['type', 'in', 'range' => EventType::keys()],
            [
                ['event_date_start', 'event_date_end'], 'datetime',
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

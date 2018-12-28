<?php

namespace lib\services;

use api\forms\event\EventForm;
use common\base\Assert;
use common\entities\event\Event;
use common\entities\news\News;
use common\entities\user\User;
use common\forms\event\SearchEventForm;
use yii\base\Component;
use yii\data\ActiveDataProvider;

class EventService extends Component
{
    public function apiCreate(EventForm $form): Event
    {
        return $this->create($form);
    }

    private function create(EventForm $form)
    {
        $tr = \Yii::$app->db->beginTransaction();

        /** @var Event $news */
        $item = new Event();

        $item->title = $form->title;
        $item->description = $form->description;

        $item->country_id = $form->geo->country_id;
        $item->city_id = $form->geo->city_id;
        $item->region_id = $form->geo->region_id;

        $item->photo_id = $form->photo->photo_id;

        $item->owner_id = User::authUser()->id;

        $item->type = $form->type;

        $item->event_date_end = $form->event_date_end ;
        $item->event_date_start = $form->event_date_start;

        $item->longitude = $form->longitude;
        $item->latitude = $form->latitude;

        $item->is_hide = $form->is_hide;

        Assert::true($item->save(false));

        $item->attachPhotos($form->photos);

        $tr->commit();

        return $item;

    }

    public function search(SearchEventForm $form)
    {
        $query = $this->initSearchQuery($form);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'enableMultiSort' => true,
                'attributes'      => [],
                'defaultOrder' =>[
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        return $dataProvider;
    }

    private function initSearchQuery(SearchEventForm $form)
    {
        $select = [
            'event.*'
        ];

        $joinWith = [];

        $query = Event::find()
            ->with([
                'owner.profile',
                'photo'
            ])
            ->distinct();

        if($form->history){
            $query->history()
                ->select($select);
            return $query;
        }

        if($form->archive){
            $query->history()
                ->select($select)
                ->archived();
            return $query;
        }


        $query->dateBetween($form->event_date_start, $form->event_date_end)
        ->byGeo($form);


        if (!empty($form->favorite))
        {
            $joinWith[] = 'favorites';
            $select[] = 'user_favorite.model';
        }
//
        if($form->archive)


//        empty($form->archive)
//            ? $query->notArchived()
//            : $query->archived();



        $query->select($select);
        if (!empty($joinWith))
            $query->joinWith($joinWith);

        dd($query->createCommand()->getRawSql());

        return $query;
    }

    public function apiUpdate(Event $item, EventForm $form): Event
    {
        $data = array_merge(
            $form->getAttributes(),
            $form->geo->getAttributes(),
            $form->photo->getAttributes()
        );

        $tr = \Yii::$app->db->beginTransaction();

        $item->detachPhotos();
        $item->attachPhotos($form->photos);

        $item->load($data, '');
        Assert::save($item->save(false));

        $tr->commit();

        return $item;
    }

    public function delete(Event $item)
    {
        $item->deleteAllCountersByThisModel();
        Assert::isRemoved($item->delete());
    }
}

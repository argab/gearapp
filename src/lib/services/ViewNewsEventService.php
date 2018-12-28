<?php

namespace lib\services;
use common\entities\event\Event;
use common\entities\news\News;
use common\entities\views\ViewNewsEvents;
use common\forms\news\SearchViewNewsEventsForm;
use yii\base\Component;
use yii\data\ActiveDataProvider;

class ViewNewsEventService extends Component
{

    public function search(SearchViewNewsEventsForm $form)
    {
        $query = $this->initSearchQuery($form);

//        dd($query->createCommand()->getRawSql());

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'enableMultiSort' => false,
                'attributes'      => [],
                'defaultOrder' =>[
                    'created_at' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        
        return $dataProvider;
    }

    private function initSearchQuery(SearchViewNewsEventsForm $form)
    {
        $query = ViewNewsEvents::find()
            ->distinct();

        $query->with([
                'tags',
                'owner.profile',
                'photo',
                'country',
                'city'
            ]);

        if($form->history){
            $query->history();
            return $query;
        }

//        if($form->archive){
//            $query->history()
//                ->archived();
//            return $query;
//        }

        $query
//            ->moderated()
            ->postedNews();


        return $query;
    }

    public function serializeItems($items)
    {
        $result = [];
        foreach ($items as $item){
            if($item->class == ViewNewsEvents::CLASS_NEWS){
                $result[] = News::serializeItem($item);
                continue;
            }

            $result[] = Event::serializeItem($item);
        }

        return $result;
    }

}

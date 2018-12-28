<?php

namespace lib\services;

use api\forms\news\NewsForm;
use common\base\Assert;
use common\dictionaries\NewsStatus;
use common\entities\news\News;
use common\entities\user\User;
use common\forms\news\SearchNewsForm;
use yii\base\Component;
use yii\data\ActiveDataProvider;

class NewsService extends Component
{
    public function apiCreate(NewsForm $form): News
    {
        return $this->create($form, NewsStatus::IN_MODERATION);
    }

    private function create(NewsForm $form, $status)
    {
        $tr = \Yii::$app->db->beginTransaction();

        /** @var News $news */
        $news = new News();

        $news->title = $form->title;
        $news->description = $form->description;

        $news->country_id = $form->geo->country_id;
        $news->city_id = $form->geo->city_id;
        $news->region_id = $form->geo->region_id;

        $news->photo_id = $form->photo->photo_id;

        $news->owner_id = User::authUser()->id;

        $news->type = $form->type;
        $news->status = $status;

        $news->post_date = $form->post_date ?? date('Y-m-d H:i:s');
        $news->post_date_close = $form->post_date_close;

        Assert::true($news->save(false));

        $news->attachPhotos($form->photos);

        $news->attachTags($form->tags);

        $tr->commit();

        return $news;

    }

    public function search(SearchNewsForm $form)
    {
        $query = $this->initSearchQuery($form);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'enableMultiSort' => false,
                'attributes'      => ['id', 'likes', 'post_data', 'shares', 'stars', 'views', 'updated_at'],
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

    private function initSearchQuery(SearchNewsForm $form)
    {
        $select = [
            'news.*',
//            'user_favorite.model_id'
        ];

        $query = News::find()
            ->with([
                'tags',
                'owner.profile',
                'photo',
                'country',
                'city'
            ])
//            ->leftJoin('user_favorite', "user_favorite.model_id = news.id and user_favorite.model = '" . News::class . "'" )
            ->distinct();
    
        
        if($form->history){
            $query->hisotry()
                ->select($select);
            return $query;
        }
        
        if($form->archive){
            $query->hisotry()
                ->select($select)
                ->archived();
            return $query;
        }
    
        if ($form->favorite) {
            $select[] = 'user_favorite.model';
        
            $query->joinWith([
                'favorites'
            ])->select($select);
        
            return $query;
        }
    
    
        $query
            ->postBetween($form->post_date_from, $form->post_date_to)
            ->posted()
//            ->moderated()
            ->byGeo($form)
            ->byUser($form->owner_ids);


        if($form->role){
            $select[] = 'auth_assignments.item_name';
            $query->leftJoin('auth_assignments', 'auth_assignments.user_id = news.owner_id')
                ->andWhere(['item_name' => $form->role]);
        }

        $query->select($select);

//        dd($query->createCommand()->getRawSql());

        return $query;
    }

    public function apiUpdate(News $item, NewsForm $form): News
    {
        $data = array_merge(
            $form->getAttributes(),
            $form->geo->getAttributes(),
            $form->photo->getAttributes()
        );

        $tr = \Yii::$app->db->beginTransaction();

        $item->detachTags();
        $item->attachTags($data['tags']);
        //        unset($data['tags']);

        $item->detachPhotos();
        $item->attachPhotos($form->photos);

        $item->load($data, '');
        Assert::save($item->save(false));

        $tr->commit();

        return $item;
    }

    public function delete(News $item)
    {
        Assert::isRemoved($item->delete());
        $item->deleteAllCountersByThisModel();
    }
}

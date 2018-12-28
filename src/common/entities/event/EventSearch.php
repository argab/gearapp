<?php

namespace common\entities\news;

use common\dictionaries\EventType;
use common\entities\event\Event;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NewsSearch represents the model behind the search form of `common\entities\news\News`.
 */
class EventSearch extends Event
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'owner_id', 'country_id', 'city_id', 'region_id', 'photo_id',  'type', 'views', 'likes', 'shares', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description'], 'safe'],
            [['event_date_start', 'event_date_end'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Event::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'region_id' => $this->region_id,
            'photo_id' => $this->photo_id,
            'type' => $this->type,
            'event_date_start' => $this->event_date_start,
            'event_date_end' => $this->event_date_end,
            'views' => $this->views,
            'likes' => $this->likes,
            'shares' => $this->shares,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }


    public function typeList(): array
    {
        return EventType::all();
    }
}

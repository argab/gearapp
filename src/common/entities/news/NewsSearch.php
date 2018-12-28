<?php

namespace common\entities\news;

use common\dictionaries\NewsStatus;
use common\dictionaries\NewsType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\entities\news\News;

/**
 * NewsSearch represents the model behind the search form of `common\entities\news\News`.
 */
class NewsSearch extends News
{

    public $post_date_from;
    public $post_date_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'owner_id', 'team_id', 'country_id', 'city_id', 'region_id', 'photo_id', 'status', 'type', 'views', 'likes', 'shares', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'post_date'], 'safe'],
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
        $query = News::find();

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
            'team_id' => $this->team_id,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'region_id' => $this->region_id,
            'photo_id' => $this->photo_id,
            'status' => $this->status,
            'type' => $this->type,
            'post_date' => $this->post_date,
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

    public function statusList(): array
    {
        return NewsStatus::all();
    }

    public function typeList(): array
    {
        return NewsType::all();
    }
}

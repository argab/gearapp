<?php

namespace common\entities\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\entities\user\UserProfile;

/**
 * UserProfileSearch represents the model behind the search form of `common\entities\user\UserProfile`.
 */
class UserProfileSearch extends UserProfile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'country_id', 'city_id', 'region_id', 'photo_id', 'created_at', 'updated_at'], 'integer'],
            [['first_name', 'last_name', 'description', 'organizer_name', 'organizer_legal_name', 'organizer_address', 'organizer_legal_address', 'organizer_address_index', 'organizer_legal_address_index'], 'safe'],
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
        $query = UserProfile::find();

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
            'user_id' => $this->user_id,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'region_id' => $this->region_id,
            'photo_id' => $this->photo_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'organizer_name', $this->organizer_name])
            ->andFilterWhere(['like', 'organizer_legal_name', $this->organizer_legal_name])
            ->andFilterWhere(['like', 'organizer_address', $this->organizer_address])
            ->andFilterWhere(['like', 'organizer_legal_address', $this->organizer_legal_address])
            ->andFilterWhere(['like', 'organizer_address_index', $this->organizer_address_index])
            ->andFilterWhere(['like', 'organizer_legal_address_index', $this->organizer_legal_address_index]);

        return $dataProvider;
    }
}

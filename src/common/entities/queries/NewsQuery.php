<?php

namespace common\entities\queries;

use common\dictionaries\NewsStatus;
use common\entities\user\User;
use yii\db\ActiveQuery;

class NewsQuery extends ActiveQuery
{
    public function byId($id)
    {
        return $this->andWhere(['=', 'id', $id])->limit(1);
    }

    public function postBetween($post_date_from = null, $post_date_to = null): self
    {
        if ( ! empty($post_date_from) || ! empty($post_date_to))
            return $this->andFilterWhere(['between', 'post_date', $post_date_from, $post_date_to]);

        return $this;
    }

    public function posted()
    {
        return $this
            ->andWhere(['<', 'post_date', date('Y-m-d H:i:s')])
            ->andWhere(['>', 'post_date_close', date('Y-m-d H:i:s')]);
    }

    public function archived()
    {
        return $this->andWhere(['<', 'post_date_close', date('Y-m-d H:i:s')]);
    }

    public function draft()
    {
        return $this->andWhere(['=', 'status', NewsStatus::DRAFT]);
    }

    public function notArchived()
    {
        return $this->andWhere(['=', 'is_archive', 0]);
    }

    public function moderated()
    {
        return $this->andWhere(['=', 'status', NewsStatus::PUBLISHED]);
    }

    public function byGeo($form): self
    {
        if ( ! empty($form->city_id))
            $this->andWhere(['city_id' => $form->city_id]);

        if ( ! empty($form->country_id))
            $this->andWhere(['country_id' => $form->country_id]);

        if ( ! empty($form->region_id))
            $this->andWhere(['region_id' => $form->region_id]);

        return $this;
    }

    public function byTeams($team_ids)
    {
        if ( ! empty($team_ids))
            return $this->andWhere(['in', 'team_id', $team_ids]);

        return $this;
    }

    public function byUser($user_ids)
    {
        if (!empty($user_ids))
            return $this->andWhere(['in', 'owner_id', $user_ids]);

        return $this;
    }
    
    public function hisotry()
    {
        return $this->andWhere(['=','owner_id', User::authUser()->id]);
    }
}

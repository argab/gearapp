<?php

namespace common\entities\queries;

use common\dictionaries\NewsStatus;
use common\entities\user\User;
use yii\db\ActiveQuery;

class EventQuery extends ActiveQuery
{
    public function dateBetween($event_date_start = null, $event_date_end = null): self
    {
        if ( ! empty($event_date_start) || ! empty($event_date_end))
            return $this
                ->andFilterWhere(['between', 'event_date_start', $event_date_start, $event_date_end])
                ->andFilterWhere(['between', 'event_date_end', $event_date_start, $event_date_end]);

        return $this;
    }

    public function archived()
    {
        return $this->andWhere(['>', 'event_date_end', date('Y-m-d H:i:s')]);
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

    public function history()
    {
        return $this->andWhere(['=', 'owner_id', User::authUser()->id]);
    }


}

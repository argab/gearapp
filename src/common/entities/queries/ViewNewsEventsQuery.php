<?php

namespace common\entities\queries;

use common\dictionaries\NewsStatus;
use common\entities\user\User;
use common\entities\views\ViewNewsEvents;
use yii\db\ActiveQuery;

class ViewNewsEventsQuery extends ActiveQuery
{


    public function postedNews()
    {
        return $this
            ->orWhere([
                'OR',
                [
                    'OR',
                    ['<', 'post_date', date('Y-m-d H:i:s')],
                    ['post_date' => null]
                ],
                [
                    'OR',
                    ['<', 'post_date_close', date('Y-m-d H:i:s')],
                    ['post_date_close' => null]
                ]
            ]);
    }


    public function moderated()
    {
        return $this
            ->andWhere([
                'AND',
                ['class' => ViewNewsEvents::CLASS_NEWS],
                ['=', 'status', NewsStatus::PUBLISHED]
            ]);
    }

    public function archived()
    {
        return $this
            ->andWhere([
                'AND',
                [
                    'OR',
                    ['>', 'post_date_close', date('Y-m-d H:i:s')],
                    ['post_date_close' => null]
                ],
                [
                    'OR',
                    ['>', 'event_date_end', date('Y-m-d H:i:s')],
                    ['event_date_end' => null]
                ],
            ]);
    }


    public function history()
    {
        return $this->andWhere(['=', 'owner_id', User::authUser()->id]);
    }
}

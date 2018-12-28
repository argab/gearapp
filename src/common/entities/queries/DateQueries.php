<?php
namespace common\entities\queries;


use DateTime;

/**
 * @package common\models\queries
 */
trait DateQueries
{
    /**
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return self
     */
    public function createdInInterval(DateTime $from, DateTime $to): self
    {
        $tableName = $this->getTablesUsedInFrom();

        return $this->andWhere(
            [
                'between',
                reset($tableName).'.created_at',
                $from->format('Y-m-d H:i:s'),
                $to->format('Y-m-d H:i:s'),
            ]
        );
    }
}

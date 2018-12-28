<?php
namespace common\traits;

use common\entities\geo\Cities;
use common\entities\geo\Countries;
use common\entities\geo\Regions;

trait GeoRelationTrait
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['city_id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::class, ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::class, ['region_id' => 'region_id']);
    }

}

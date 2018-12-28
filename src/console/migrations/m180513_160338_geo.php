<?php

use yii\db\Migration;

/**
 * Class m180513_160338_country
 */
class m180513_160338_geo extends Migration
{

	public function safeUp()
	{
        $this->createTable('cities', [
            'city_id'    => $this->primaryKey(),
            'country_id' => $this->integer(11)->notNull(),
            'important'  => $this->tinyInteger(1)->notNull(),
            'region_id'  => $this->integer(11),
            'title_ru'   => $this->string(150),
            'area_ru'    => $this->string(150),
            'region_ru'  => $this->string(150),
            'title_en'   => $this->string(150),
            'area_en'    => $this->string(150),
            'region_en'  => $this->string(150),
        ]);

        $this->createTable('countries', [
            'country_id' => $this->primaryKey(),
            'title_ru'   => $this->string(60),
            'title_en'   => $this->string(60)
        ]);

        $this->createTable('regions', [
            'region_id'  => $this->primaryKey(),
            'country_id' => $this->integer(11)->notNull(),
            'title_ru'   => $this->string(150),
            'title_en'   => $this->string(150),
        ]);

        $this->addForeignKey('fk-cities-countries', 'cities', 'country_id', 'countries', 'country_id');
        $this->addForeignKey('fk-cities-regions', 'cities', 'region_id', 'regions', 'region_id','CASCADE', 'CASCADE');
        $this->addForeignKey('fk-regions-countries', 'regions', 'country_id', 'countries', 'country_id','CASCADE', 'CASCADE');

        $this->addForeignKey('fk-user_profile-countries', 'user_profile', 'country_id', 'countries', 'country_id');
        $this->addForeignKey('fk-user_profile-cities', 'user_profile', 'city_id', 'cities', 'city_id');
        $this->addForeignKey('fk-user_profile-regions', 'user_profile', 'region_id', 'regions', 'region_id');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('cities');
		$this->dropTable('countries');
		$this->dropTable('regions');
	}

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180513_160338_country cannot be reverted.\n";

        return false;
    }
    */
}

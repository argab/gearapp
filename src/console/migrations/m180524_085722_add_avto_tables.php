<?php

use yii\db\Migration;

/**
 * Class m180524_085722_add_avto_tables
 */
class m180524_085722_add_avto_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('car_brand', [
            'id'    => $this->primaryKey(),
            'title' => $this->string()
        ]);

        $this->createTable('car_model', [
            'id'    => $this->primaryKey(),
            'cb_id' => $this->integer(),
            'title' => $this->string()
        ]);

        $this->addForeignKey(
            'fk-car_model-car_brand',
            'car_model',
            'cb_id',
            'car_brand',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable('car_class', [
            'id'    => $this->primaryKey(),
            'title' => $this->string()
        ]);

        $this->createTable('car_transmission', [
            'id'    => $this->primaryKey(),
            'title' => $this->string()
        ]);

        $this->createTable('car', [
            'id'            => $this->primaryKey(),
            'user_id'       => $this->integer(),
            'team_id'       => $this->integer(),
            'cb_id'         => $this->integer(),
            'cm_id'         => $this->integer(),
            'cc_id'         => $this->integer(),
            'ct_id'         => $this->integer(),
            'year'          => $this->integer(4),
            'volume'        => $this->float(),
            'horsepower'    => $this->float(),
            'main_photo_id' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

	    $this->addForeignKey(
		    'fk-car-user',
		    'car',
		    'user_id',
		    'user',
		    'id'
	    );

        $this->addForeignKey(
            'fk-car-car_brand',
            'car',
            'cb_id',
            'car_brand',
            'id'
        );

        $this->addForeignKey(
            'fk-car-car_model',
            'car',
            'cm_id',
            'car_model',
            'id'
        );

        $this->addForeignKey(
            'fk-car-car_class',
            'car',
            'cc_id',
            'car_class',
            'id'
        );

        $this->addForeignKey(
            'fk-car-car_transmission',
            'car',
            'ct_id',
            'car_transmission',
            'id'
        );

	    $this->addForeignKey(
		    'fk-car-photo',
		    'car',
		    'main_photo_id',
		    'files',
		    'id'
	    );

        $this->createTable('car_photo', [
            'car_id'    => $this->integer(),
            'photo_id'    => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-car_photo-car_id',
            'car_photo',
            'car_id',
            'car',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-car_photo-photo_id',
            'car_photo',
            'photo_id',
            'files',
            'id',
            'CASCADE',
            'CASCADE'
        );



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('car_brand');
        $this->dropTable('car_model');
        $this->dropTable('car_class');
        $this->dropTable('car_transmission');
        $this->dropTable('car_photo');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180524_085722_add_avto_tables cannot be reverted.\n";

        return false;
    }
    */
}

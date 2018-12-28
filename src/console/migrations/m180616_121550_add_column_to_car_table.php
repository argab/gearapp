<?php

use yii\db\Migration;

/**
 * Class m180616_121550_add_column_to_car_table
 */
class m180616_121550_add_column_to_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('car','equipment', $this->string(500)->after('main_photo_id'));
        $this->addColumn('car','information', $this->string(500)->after('equipment'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180616_121550_add_column_to_car_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180616_121550_add_column_to_car_table cannot be reverted.\n";

        return false;
    }
    */
}

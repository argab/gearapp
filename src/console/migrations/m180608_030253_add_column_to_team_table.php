<?php

use yii\db\Migration;

/**
 * Class m180608_030253_add_column_to_team_table
 */
class m180608_030253_add_column_to_team_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('team', 'description', $this->string(340));
        $this->addColumn('team', 'country_id', $this->integer());
        $this->addColumn('team', 'city_id', $this->integer());
        $this->addColumn('team', 'region_id', $this->integer());
    }




    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180608_030253_add_column_to_team_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180608_030253_add_column_to_team_table cannot be reverted.\n";

        return false;
    }
    */
}

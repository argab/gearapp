<?php

use yii\db\Migration;

/**
 * Class m180524_145754_add_avto_initial_data
 */
class m180525_145754_add_online_user_fileds_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('user', 'online', $this->boolean()->defaultValue(false));
    	$this->addColumn('user', 'last_online', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180524_145754_add_avto_initial_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180524_145754_add_avto_initial_data cannot be reverted.\n";

        return false;
    }
    */
}

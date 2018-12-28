<?php

use yii\db\Migration;

/**
 * Handles the creation of table `counter`.
 */
class m180630_163152_create_counter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('counter', [
            'id'         => $this->primaryKey(),
            'model'      => $this->string(30),
            'model_id'   => $this->integer()->unsigned(),
            'type'       => $this->smallInteger(2)->unsigned(),
            'user_id'    => $this->integer()->unsigned(),
            'created_at' => $this->integer()->unsigned()
        ]);

        $this->createIndex('ix-model','counter', 'model');
        $this->createIndex('ix-model_id','counter', 'model_id');
        $this->createIndex('ix-user_id','counter', 'user_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('counter');
    }
}

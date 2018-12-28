<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscribes`.
 */
class m180520_161816_create_subscribes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_subscribe', [
            'id'            => $this->primaryKey(),
            'subscriber_id' => $this->integer()->comment('Подписчик'),
            'user_id'       => $this->integer()->comment('К кому подписка'),
            'team_id'       => $this->integer()->comment('К кому подписка'),
            'event_id'      => $this->integer()->comment('К кому подписка'),
            'is_active'     => $this->boolean()->defaultValue(1),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-user_subscribe-user',
            'user_subscribe',
            'subscriber_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('user_id', 'user_subscribe', 'user_id');
        $this->createIndex('team_id', 'user_subscribe', 'team_id');
        $this->createIndex('event_id', 'user_subscribe', 'event_id');
        $this->createIndex('is_active', 'user_subscribe', 'is_active');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_subscribe');
    }
}

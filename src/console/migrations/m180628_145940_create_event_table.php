<?php

use yii\db\Migration;

/**
 * Handles the creation of table `event`.
 */
class m180628_145940_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('event', [

            'id'          => $this->primaryKey(),
            'title'       => $this->string()->notNull(),
            'description' => $this->text(),

            'owner_id' => $this->integer()->notNull(),

            'country_id' => $this->integer(),
            'city_id'    => $this->integer(),
            'region_id'  => $this->integer(),

            'latitude'  => $this->decimal(11, 8),
            'longitude' => $this->decimal(11, 8),

            'photo_id' => $this->integer(),

            'type' => $this->smallInteger(1),

            'is_hide' => $this->boolean()->defaultValue(false),

            'event_date_start' => $this->dateTime(),
            'event_date_end'   => $this->dateTime(),

            'views'  => $this->integer()->notNull()->defaultValue(0),
            'likes'  => $this->integer()->notNull()->defaultValue(0),
            'shares' => $this->integer()->notNull()->defaultValue(0),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer()

        ]);

        $this->addForeignKey('fk-event-owner', 'event', 'owner_id', 'user', 'id');

        $this->addForeignKey('fk-event-countries', 'event', 'country_id', 'countries', 'country_id');
        $this->addForeignKey('fk-event-cities', 'event', 'city_id', 'cities', 'city_id');
        $this->addForeignKey('fk-event-regions', 'event', 'region_id', 'regions', 'region_id');

        $this->addForeignKey('fk-event-file', 'event', 'photo_id', 'files', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('event');
    }
}

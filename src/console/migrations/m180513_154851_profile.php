<?php

use yii\db\Migration;

/**
 * Class m180513_154851_profile
 */
class m180513_154851_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_profile', [

            'id' => $this->primaryKey(),

            'user_id' => $this->integer(11),

            'first_name'  => $this->string(),
            'last_name'   => $this->string(),
            'country_id'  => $this->integer(11),
            'city_id'     => $this->integer(11),
            'region_id'   => $this->integer(11),
            'photo_id'    => $this->integer(11),
            'description' => $this->string(340),

            // Наименование организатора*
            // Юр Наименование
            // Юридический адрес индекс
            // Физический адрес индекс

            'organizer_name'          => $this->string(),
            'organizer_legal_name'    => $this->string(),
            'organizer_address'       => $this->string(),
            'organizer_legal_address' => $this->string(),

            'organizer_address_index' => $this->string(),
            'organizer_legal_address_index' => $this->string(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

        ]);

        $this->addForeignKey(
            'fk-user_profile-user-id',
            'user_profile',
            'user_id',
            'user',
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
        $this->dropTable('user_profile');
    }

}

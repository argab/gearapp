<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_favorite`.
 */
class m180623_134636_create_user_favorite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_favorite', [
            'id'       => $this->primaryKey(),
            'user_id'  => $this->integer()->notNull(),
            'model'    => $this->string(30),
            'model_id' => $this->integer()
        ]);

        $this->addForeignKey('fk-user_favorite-user', 'user_favorite', 'user_id', 'user', 'id');



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_favorite');
    }
}

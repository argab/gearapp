<?php

use yii\db\Migration;

/**
 * Handles the creation of table `photos`.
 */
class m180625_134635_create_photos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('photos', [
            'id'         => $this->primaryKey(),
            'file_id'    => $this->integer()->notNull(),
            'model'      => $this->string(30),
            'model_id'   => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-photos-files', 'photos', 'file_id', 'files', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('photos');
    }
}

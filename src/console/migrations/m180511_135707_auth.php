<?php

use yii\db\Migration;

/**
 * Class m180511_135707_auth
 */
class m180511_135707_auth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->createTable('auth', [
		    'id' => $this->primaryKey(),
		    'user_id' => $this->integer()->notNull(),
		    'source' => $this->string()->notNull(),
		    'source_id' => $this->string()->notNull(),
	    ]);

	    $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropTable('auth');
        return false;
    }


}

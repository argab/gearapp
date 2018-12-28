<?php

use yii\db\Migration;

/**
 * Class m180509_043610_token
 */
class m180509_043610_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
	    }

	    $this->createTable('{{%tokens}}', [
		    'id' => $this->primaryKey(),
		    'user_id' => $this->integer(11)->notNull(),
		    'token' => $this->string(),
		    'expire_time' => $this->string(),
	    ], $tableOptions);

	    $this->addForeignKey('{{%fk-tokens-user_id}}', '{{%tokens}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180509_043610_token cannot be reverted.\n";

	    $this->dropTable('{{%tokens}}');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180509_043610_token cannot be reverted.\n";

        return false;
    }
    */
}

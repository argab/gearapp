<?php

use yii\db\Migration;

/**
 * Class m180514_155407_photo
 */
class m180514_155407_files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->createTable('files', [
		    'id'  => $this->primaryKey(),
		    'user_id'   => $this->integer(11),
		    'hash'      => $this->string()->unique(),
		    'name'      => $this->string(),
		    'type'      => $this->string(),
		    'size'      => $this->integer(),
		    'file_name' => $this->string(),
		    'path'      => $this->string(),
		    'is_image'  => $this->tinyInteger(1)->defaultValue(1),
	    ]);

	    $this->addForeignKey(
		    'fk-files-user_id',
		    'files',
		    'user_id',
		    'user',
		    'id'
	    );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropTable('files');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180514_155407_photo cannot be reverted.\n";

        return false;
    }
    */
}

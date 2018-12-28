<?php

use yii\db\Migration;

/**
 * Class m180508_092301_change_auth_assignments_table
 */
class m180508_092301_change_auth_assignments_table extends Migration
{
	public function safeUp()
	{
		$this->alterColumn('{{%auth_assignments}}', 'user_id', $this->integer()->notNull());

		$this->createIndex('{{%idx-auth_assignments-user_id}}', '{{%auth_assignments}}', 'user_id');
	}

	public function down()
	{
		$this->dropIndex('{{%idx-auth_assignments-user_id}}', '{{%auth_assignments}}');

		$this->alterColumn('{{%auth_assignments}}', 'user_id', $this->string(64)->notNull());
	}
}

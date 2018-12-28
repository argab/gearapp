<?php

use yii\db\Migration;

/**
 * Class m180508_093008_add_user_role
 */
class m180508_093008_add_user_role extends Migration
{
	public function safeUp()
	{
		$this->batchInsert('{{%auth_items}}', ['type', 'name', 'description'], [
			[1, 'racer', 'Driver'],
			[1, 'gaper', 'Viewer'],
			[1, 'organizer', 'Organizer'],
			[1, 'journalist', 'Journalist'],
			[1, 'admin', 'Admin'],
		]);

//		$this->batchInsert('{{%auth_item_children}}', ['parent', 'child'], [
//			['admin', 'racer'],
//			['admin', 'gaper'],
//			['admin', 'organizer'],
//			['admin', 'journalist'],
//		]);

		// устанавливет всем пользователям роль зеваки
//		$this->execute('INSERT INTO {{%auth_assignments}} (item_name, user_id) SELECT \'gaper\', u.id FROM {{%users}} u ORDER BY u.id');
	}

	public function down()
	{
		$this->delete('{{%auth_items}}', ['name' => [
			'racer',
			'gaper',
			'organizer',
			'journalist',
			'admin',
		]]);
	}
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `garage`.
 */
class m180526_103844_create_garage_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('garage', [
			'id'      => $this->primaryKey(),
			'user_id' => $this->integer(),
			'team_id' => $this->integer(),
			'car_id'  => $this->integer(),

			'created_at' => $this->integer(),
			'updated_at' => $this->integer(),
		]);

		$this->addForeignKey(
			'fk-garage-user',
			'garage',
			'user_id',
			'user',
			'id',
			'NO ACTION',
			'NO ACTION'
		);

		$this->addForeignKey(
			'fk-garage-team',
			'garage',
			'team_id',
			'team',
			'id',
			'NO ACTION',
			'NO ACTION'

		);

		$this->addForeignKey(
			'fk-garage-car',
			'garage',
			'car_id',
			'car',
			'id',
			'NO ACTION',
			'NO ACTION'
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('garage');
	}
}

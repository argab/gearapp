<?php

use yii\db\Migration;

/**
 * Handles the creation of table `team_history`.
 */
class m180527_044828_create_team_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->createTable('team_history', [
		    'id' => $this->primaryKey(),

		    'type'        => $this->integer(2),
		    'team_id'     => $this->integer()->notNull(),
		    'title'       => $this->string(),
		    'description' => $this->text(),
		    'photo_id'    => $this->integer(),
		    'event_date'  => $this->integer(),

		    'created_at' => $this->integer(),
		    'updated_at' => $this->integer(),
	    ]);

	    $this->addForeignKey(
		    'fk-team_history-team',
		    'team_history',
		    'team_id',
		    'team',
		    'id'
	    );

	    $this->addForeignKey(
		    'fk-team_history-photo',
		    'team_history',
		    'photo_id',
		    'files',
		    'id'
	    );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('team_history');
    }
}

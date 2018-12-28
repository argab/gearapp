<?php

use yii\db\Migration;

/**
 * Class m180517_094104_team
 */
class m180517_094104_team extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('team', [
            'id'         => $this->primaryKey(),
            'creator_id' => $this->integer(),
            'title'      => $this->string(),
            'photo_id'      => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk-team-user',
            'team',
            'creator_id',
            'user',
            'id',
	        'CASCADE',
	        'CASCADE'
        );

	    $this->addForeignKey(
		    'fk-team-photo',
		    'team',
		    'photo_id',
		    'files',
		    'id'
	    );

        $this->createTable('team_members', [
            'id'         => $this->primaryKey(),
            'team_id'    => $this->integer(),
            'user_id'    => $this->integer(),
            'user_label' => $this->string(),
            'user_role'  => $this->integer(1)->defaultValue(0),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk-tm-team',
            'team_members',
            'team_id',
            'team',
            'id',
	        'CASCADE',
	        'CASCADE'
        );

        $this->addForeignKey(
            'fk-tm-user',
            'team_members',
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
        echo "m180517_094104_team cannot be reverted.\n";
        $this->delete('team');
        $this->delete('team_members');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180517_094104_team cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m180523_032556_static_info_groups_table
 */
class m180523_032556_static_info_groups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE `static_info_groups` (
                `key` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`key`),
                UNIQUE INDEX `name` (`name`)
            )
            COLLATE=\'utf8_general_ci\'
            ENGINE=InnoDB
            ;
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180523_032556_static_info_groups_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180523_032556_static_info_groups_table cannot be reverted.\n";

        return false;
    }
    */
}

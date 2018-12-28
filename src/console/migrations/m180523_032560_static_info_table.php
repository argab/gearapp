<?php

use yii\db\Migration;

/**
 * Class m180523_032535_static_info_table
 */
class m180523_032560_static_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE `static_info` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `group_key` INT(11) UNSIGNED NULL DEFAULT NULL,
                `key` VARCHAR(255) NOT NULL,
                `name` VARCHAR(255) NOT NULL,
                `value` TEXT NOT NULL,
                `show` TINYINT(1) UNSIGNED NULL DEFAULT \'0\',
                `priority` INT(11) NULL DEFAULT \'0\',
                PRIMARY KEY (`id`),
                UNIQUE INDEX `key` (`key`),
                INDEX `group_key` (`group_key`),
                CONSTRAINT `FK_static_info_static_info_groups` FOREIGN KEY (`group_key`) REFERENCES `static_info_groups` (`key`) ON UPDATE CASCADE ON DELETE CASCADE
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
        echo "m180523_032535_static_info_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180523_032535_static_info_table cannot be reverted.\n";

        return false;
    }
    */
}

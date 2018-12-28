<?php

use yii\db\Migration;

/**
 * Class m180518_061447_add_geo
 */
class m180518_061447_add_geo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $file = __DIR__ . '/data/city_country_regions.sql';
        if(!file_exists($file))
            throw new RuntimeException('File not found' . $file);

        $sql = file_get_contents($file);

        $this->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180518_061447_add_geo cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180518_061447_add_geo cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m180616_122151_add_agreement_text_to_get_info_table
 */
class m180616_122151_add_agreement_text_to_get_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $item = new \common\entities\info\StaticInfo();
        $item->key = 'agreement';
        $item->name = 'agreement name';
        $item->value = 'agreement value';
        $item->show = 1;
        $item->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180616_122151_add_agreement_text_to_get_info_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180616_122151_add_agreement_text_to_get_info_table cannot be reverted.\n";

        return false;
    }
    */
}

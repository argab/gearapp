<?php

use yii\db\Migration;

/**
 * Class m180517_032049_country_phone_codes
 */
class m180517_032049_country_phone_codes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('countries_phone_code', [
            'id'           => $this->primaryKey(),
            'phone'        => $this->string(10),
            'country_name' => $this->string(50),
            'country_code' => $this->string(2),
        ]);

        $this->insertDataFromJson('countries_phone_code');
    }

    public function insertDataFromJson()
    {
        $file = __DIR__ . '/data/countries.json';
        if(file_exists($file)){
            $jsonArr  = json_decode(file_get_contents($file), true);
            foreach ($jsonArr as $key => $item){
                Yii::$app->db->createCommand()->insert('countries_phone_code',[
                    'phone' => $item['phone'],
                    'country_name' => $item['name'],
                    'country_code' => $key,
                ])->execute();
            }
        }
//
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180517_032049_country_phone_codes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180517_032049_country_phone_codes cannot be reverted.\n";

        return false;
    }
    */
}

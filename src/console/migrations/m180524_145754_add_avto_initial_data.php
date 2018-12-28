<?php

use yii\db\Migration;

/**
 * Class m180524_145754_add_avto_initial_data
 */
class m180524_145754_add_avto_initial_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    	$car = new \common\entities\car\CarBrand(['title' => 'Audi']);
    	$car->save();

    	(new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '50']))->save();
    	(new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '60']))->save();
    	(new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '75']))->save();
    	(new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '80']))->save();
    	(new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '90']))->save();

	    $car = new \common\entities\car\CarBrand(['title' => 'Alfa Romeo']);
	    $car->save();

	    (new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '145']))->save();
	    (new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '146']))->save();
	    (new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '147']))->save();
	    (new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '155']))->save();
	    (new \common\entities\car\CarModel(['cb_id' => $car->id, 'title' => '156']))->save();


	    (new \common\entities\car\CarClass(['title' => 'седан']))->save();
	    (new \common\entities\car\CarClass(['title' => 'универсал']))->save();
	    (new \common\entities\car\CarClass(['title' => 'кабриолет']))->save();

	    (new \common\entities\car\CarTransmission(['title' => 'механика']))->save();
	    (new \common\entities\car\CarTransmission(['title' => 'типтроник']))->save();
	    (new \common\entities\car\CarTransmission(['title' => 'вариатор']))->save();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180524_145754_add_avto_initial_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180524_145754_add_avto_initial_data cannot be reverted.\n";

        return false;
    }
    */
}

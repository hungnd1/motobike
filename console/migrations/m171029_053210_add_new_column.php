<?php

use yii\db\Migration;

class m171029_053210_add_new_column extends Migration
{
    public function up()
    {
        $this->addColumn('weather_detail','wnddtxt',$this->string(15));
        $this->addColumn('weather_detail','wtxt',$this->string(255));
    }

    public function down()
    {
        echo "m171029_053210_add_new_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

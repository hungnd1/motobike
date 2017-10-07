<?php

use yii\db\Migration;

class m171007_150439_add_new_clouymn extends Migration
{
    public function up()
    {
        $this->addColumn('weather_detail','PROPRCP',$this->integer(11));
    }

    public function down()
    {
        echo "m171007_150439_add_new_clouymn cannot be reverted.\n";

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

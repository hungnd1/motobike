<?php

use yii\db\Migration;

class m181023_095918_alter_weather_detail_subscriber extends Migration
{
    public function up()
    {
        $this->alterColumn('subscriber','weather_detail_id',$this->string(255));
    }

    public function down()
    {
        echo "m181023_095918_alter_weather_detail_subscriber cannot be reverted.\n";

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

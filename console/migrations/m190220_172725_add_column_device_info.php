<?php

use yii\db\Migration;

class m190220_172725_add_column_device_info extends Migration
{
    public function up()
    {
        $this->addColumn('device_info','last_subscriber_id',$this->integer(11));
    }

    public function down()
    {
        echo "m190220_172725_add_column_device_info cannot be reverted.\n";

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

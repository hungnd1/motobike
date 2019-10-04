<?php

use yii\db\Migration;

class m190930_035129_alter_table_station extends Migration
{
    public function up()
    {
        $this->addColumn('station','station_key',$this->string(255));
    }

    public function down()
    {
        echo "m190930_035129_alter_table_station cannot be reverted.\n";

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

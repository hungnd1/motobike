<?php

use yii\db\Migration;

class m171113_072329_add_new_column_station_id extends Migration
{
    public function up()
    {

        $this->addColumn('feedback','station_id',$this->integer(11));
    }

    public function down()
    {
        echo "m171113_072329_add_new_column_station_id cannot be reverted.\n";

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

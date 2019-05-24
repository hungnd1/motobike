<?php

use yii\db\Migration;

class m190524_054000_add_new_column_provice extends Migration
{
    public function up()
    {
        $this->addColumn('province','province_name_sms',$this->string(255));
    }

    public function down()
    {
        echo "m190524_054000_add_new_column_provice cannot be reverted.\n";

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

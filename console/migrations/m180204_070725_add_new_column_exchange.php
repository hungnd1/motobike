<?php

use yii\db\Migration;

class m180204_070725_add_new_column_exchange extends Migration
{
    public function up()
    {
        $this->addColumn('exchange_buy','province_id',$this->integer(11));
    }

    public function down()
    {
        echo "m180204_070725_add_new_column_exchange cannot be reverted.\n";

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

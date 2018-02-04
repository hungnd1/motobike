<?php

use yii\db\Migration;

class m180204_062838_alter_column_exchange extends Migration
{
    public function up()
    {
        $this->addColumn('exchange','province_id',$this->integer(11));
        $this->dropColumn('exchange','total_quality_id');
        $this->dropColumn('exchange','sold_id');
        $this->addColumn('exchange','total_quantity',$this->string(10));
    }

    public function down()
    {
        echo "m180204_062838_alter_column_exchange cannot be reverted.\n";

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

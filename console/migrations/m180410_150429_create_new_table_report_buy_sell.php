<?php

use yii\db\Migration;

class m180410_150429_create_new_table_report_buy_sell extends Migration
{
    public function up()
    {
        $this->createTable('report_buy_sell',[
           'id'=>$this->primaryKey(11),
           'province_id' => $this->integer(11),
           'type_coffee'=>$this->integer(11),
            'total_buy' => $this->integer(11),
            'total_sell' => $this->integer(11),
            'report_date' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m180410_150429_create_new_table_report_buy_sell cannot be reverted.\n";

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

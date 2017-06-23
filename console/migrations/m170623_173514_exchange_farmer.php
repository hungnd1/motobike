<?php

use yii\db\Migration;

class m170623_173514_exchange_farmer extends Migration
{
    public function up()
    {
        $this->createTable('exchange_farmer',[
           'id'=> $this->primaryKey(),
            'quanlity'=>$this->double(3),
            'sold'=>$this->double(11),
            'desire_to_sell' => $this->double(3),
            'created_at'=>$this->integer(11),
            'updated_at'=>$this->integer(11),
            'subscriber_id'=>$this->integer(11),
            'type_coffee'=>$this->integer(11)

        ]);
    }

    public function down()
    {
        echo "m170623_173514_exchange_farmer cannot be reverted.\n";

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

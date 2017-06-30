<?php

use yii\db\Migration;

class m170630_091850_create_table_exchange_buy extends Migration
{
    public function up()
    {
        $this->createTable('exchange_buy',[
            'id' => $this->primaryKey(),
            'subscriber_id' => $this->integer(11),
            'price_buy' => $this->string(255),
            'total_quantity' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m170630_091850_create_table_exchange_buy cannot be reverted.\n";

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

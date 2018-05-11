<?php

use yii\db\Migration;

class m180511_152627_create_table_logs extends Migration
{
    public function up()
    {
        $this->createTable('subscriber_transaction', [
            'id' => $this->primaryKey(11),
            'subscriber_id' => $this->integer(11),
            'type' => $this->integer(11),
            'service_id' => $this->integer(11),
            'transaction_time' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'status' => $this->integer(11),
            'balance' => $this->integer(11),
            'description' => $this->string(500),
            'error_code' => $this->string(100),
            'subscriber_service_asm_id' => $this->integer(11),
            'expired_time' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m180511_152627_create_table_logs cannot be reverted.\n";

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

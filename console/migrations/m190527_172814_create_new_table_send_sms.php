<?php

use yii\db\Migration;

class m190527_172814_create_new_table_send_sms extends Migration
{
    public function up()
    {
        $this->createTable('send_receive',[
           'id' => $this->primaryKey(11),
           'from'=>$this->string(500),
            'to' => $this->string(255),
            'text' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'status' => $this->integer(11),
            'carrier' => $this->string(255),
            'error_code' => $this->integer(11),
            'description' => $this->string(255)
        ]);
    }

    public function down()
    {
        echo "m190527_172814_create_new_table_send_sms cannot be reverted.\n";

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

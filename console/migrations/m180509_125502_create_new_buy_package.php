<?php

use yii\db\Migration;

class m180509_125502_create_new_buy_package extends Migration
{
    public function up()
    {
        $this->createTable('service', [
            'id' => $this->primaryKey(11),
            'service_name' => $this->string(255),
            'description' => $this->text(),
            'time_expired' => $this->integer(11),
            'status' => $this->integer(11),
            'image' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'price' => $this->integer()
        ]);
        $this->createTable('subscriber_group', [
            'id' => $this->primaryKey(11),
            'group_name' => $this->string(255),
            'description' => $this->text(),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);

        $this->createTable('subscriber_group_subscriber_asm',[
            'id' => $this->primaryKey(11),
            'subscriber_group_id' => $this->integer(11),
            'subscriber_id' => $this->integer(11),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
        $this->createTable('subscriber_service_asm',[
           'id' => $this->primaryKey(11),
           'service_id' => $this->integer(11),
            'time_expired' => $this->integer(11),
            'subscriber_id' => $this->integer(11),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m180509_125502_create_new_buy_package cannot be reverted.\n";

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

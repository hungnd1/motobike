<?php

use yii\db\Migration;

class m190515_161913_create_new_mo_mt extends Migration
{
    public function up()
    {
        $this->createTable('mo_mt',[
            'id' => $this->primaryKey(11),
            'from_number' => $this->string(255),
            'to_number' => $this->string(255),
            'message_mo' => $this->text(),
            'request_id' => $this->integer(11),
            'message_mt' => $this->text(),
            'status_sync'=>$this->integer(11),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m190515_161913_create_new_mo_mt cannot be reverted.\n";

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

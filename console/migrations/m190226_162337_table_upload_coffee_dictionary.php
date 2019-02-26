<?php

use yii\db\Migration;

class m190226_162337_table_upload_coffee_dictionary extends Migration
{
    public function up()
    {
        $this->createTable('subscriber_dictionary', [
            'id' => $this->primaryKey(11),
            'subscriber' => $this->integer(11),
            'image' => $this->string(255),
            'content' => $this->text(),
            'created_at' => $this->integer(11),
            'group_id' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m190226_162337_table_upload_coffee_dictionary cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170726_151456_create_table_log_data extends Migration
{
    public function up()
    {
        $this->createTable('log_data',[
            'id'=>$this->primaryKey(),
            'latitude' => $this->string(10),
            'longitude' => $this->string(10),
            'content' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'type_coffee' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m170726_151456_create_table_log_data cannot be reverted.\n";

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

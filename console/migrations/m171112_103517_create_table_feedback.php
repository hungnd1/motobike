<?php

use yii\db\Migration;

class m171112_103517_create_table_feedback extends Migration
{
    public function up()
    {
        $this->createTable('feedback',[
           'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
            'id_question' => $this->integer(11),
            'created_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        echo "m171112_103517_create_table_feedback cannot be reverted.\n";

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

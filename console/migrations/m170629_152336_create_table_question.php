<?php

use yii\db\Migration;

class m170629_152336_create_table_question extends Migration
{
    public function up()
    {
        $this->createTable('question_answer',[
           'id' => $this->primaryKey(),
            'question' => $this->text(),
            'answer'=> $this->text(),
            'created_at'=> $this->integer(11),
            'updated_at'=> $this->integer(11),
            'status' => $this->integer(11),
            'image' => $this->string(500)
        ]);
    }

    public function down()
    {
        echo "m170629_152336_create_table_question cannot be reverted.\n";

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

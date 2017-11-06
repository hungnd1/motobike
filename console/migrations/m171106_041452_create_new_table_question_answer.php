<?php

use yii\db\Migration;

class m171106_041452_create_new_table_question_answer extends Migration
{
    public function up()
    {
        $this->createTable('question', [
            'id' => $this->primaryKey(),
            'question' => $this->text(),
            'is_dropdown_list' => $this->integer(11),
        ]);
        $this->createTable('answer', [
            'id' => $this->primaryKey(),
            'answer' => $this->text(),
            'question_id' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m171106_041452_create_new_table_question_answer cannot be reverted.\n";

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

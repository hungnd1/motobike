<?php

use yii\db\Migration;

class m190723_182002_question_answer_company extends Migration
{
    public function up()
    {
        $this->createTable('company_qa',[
            'id' => $this->primaryKey(11),
            'question' => $this->text(),
            'answer'=> $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'status' => $this->integer(11),
            'company_id' => $this->integer(11),
            'farmer_id' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m190723_182002_question_answer_company cannot be reverted.\n";

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

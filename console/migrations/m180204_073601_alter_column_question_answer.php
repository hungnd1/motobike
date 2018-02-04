<?php

use yii\db\Migration;

class m180204_073601_alter_column_question_answer extends Migration
{
    public function up()
    {

        $this->addColumn('question_answer','subscriber_id',$this->integer(11));
    }

    public function down()
    {
        echo "m180204_073601_alter_column_question_answer cannot be reverted.\n";

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

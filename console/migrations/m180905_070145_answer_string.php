<?php

use yii\db\Migration;

class m180905_070145_answer_string extends Migration
{
    public function up()
    {
        $this->addColumn('question_answer','answer_string', $this->text());
    }

    public function down()
    {
        echo "m180905_070145_answer_string cannot be reverted.\n";

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

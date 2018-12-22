<?php

use yii\db\Migration;

class m181222_054519_alter_table_qa extends Migration
{
    public function up()
    {
        $this->addColumn('category', 'type', $this->integer(11));
        $this->addColumn('question_answer', 'category_id', $this->integer(11));
    }

    public function down()
    {
        echo "m181222_054519_alter_table_qa cannot be reverted.\n";

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

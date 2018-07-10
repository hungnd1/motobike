<?php

use yii\db\Migration;

class m180710_141403_alter_table_fruit extends Migration
{
    public function up()
    {
        $this->addColumn('fruit', 'parent_id', $this->integer(11));
        $this->addColumn('fruit', 'have_child', $this->integer(11));
        $this->addColumn('question', 'fruit_id', $this->integer(11));
        $this->addColumn('matrix_fertilizing', 'question_id', $this->integer(11));
    }

    public function down()
    {
        echo "m180710_141403_alter_table_fruit cannot be reverted.\n";

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

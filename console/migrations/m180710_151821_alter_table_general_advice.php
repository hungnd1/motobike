<?php

use yii\db\Migration;

class m180710_151821_alter_table_general_advice extends Migration
{
    public function up()
    {
        $this->addColumn('gap_general', 'fruit_id', $this->integer(11));
    }

    public function down()
    {
        echo "m180710_151821_alter_table_general_advice cannot be reverted.\n";

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

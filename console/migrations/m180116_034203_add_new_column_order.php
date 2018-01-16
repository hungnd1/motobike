<?php

use yii\db\Migration;

class m180116_034203_add_new_column_order extends Migration
{
    public function up()
    {
        $this->addColumn('gap_general','order',$this->integer(11)->defaultValue(0));
    }

    public function down()
    {
        echo "m180116_034203_add_new_column_order cannot be reverted.\n";

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

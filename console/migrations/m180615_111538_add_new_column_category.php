<?php

use yii\db\Migration;

class m180615_111538_add_new_column_category extends Migration
{
    public function up()
    {
        $this->addColumn('category','fruit_id',$this->integer(11));
    }

    public function down()
    {
        echo "m180615_111538_add_new_column_category cannot be reverted.\n";

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

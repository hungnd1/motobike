<?php

use yii\db\Migration;

class m180811_103932_add_new_column_category extends Migration
{
    public function up()
    {
        $this->addColumn('category','image',$this->string(500));
    }

    public function down()
    {
        echo "m180811_103932_add_new_column_category cannot be reverted.\n";

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

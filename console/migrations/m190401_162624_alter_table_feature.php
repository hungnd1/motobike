<?php

use yii\db\Migration;

class m190401_162624_alter_table_feature extends Migration
{
    public function up()
    {
        $this->addColumn('feature','order',$this->integer(11));
        $this->addColumn('feature','status', $this->integer(11));
    }

    public function down()
    {
        echo "m190401_162624_alter_table_feature cannot be reverted.\n";

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

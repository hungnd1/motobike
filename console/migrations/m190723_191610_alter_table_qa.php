<?php

use yii\db\Migration;

class m190723_191610_alter_table_qa extends Migration
{
    public function up()
    {
        $this->addColumn('company_qa','image',$this->string(500));
    }

    public function down()
    {
        echo "m190723_191610_alter_table_qa cannot be reverted.\n";

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

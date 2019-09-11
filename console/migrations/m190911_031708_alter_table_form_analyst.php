<?php

use yii\db\Migration;

class m190911_031708_alter_table_form_analyst extends Migration
{
    public function up()
    {
        $this->addColumn('form_analyst','month',$this->string(255));
    }

    public function down()
    {
        echo "m190911_031708_alter_table_form_analyst cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m190721_071607_add_new_column_description extends Migration
{
    public function up()
    {
        $this->addColumn('company','description',$this->text());
    }

    public function down()
    {
        echo "m190721_071607_add_new_column_description cannot be reverted.\n";

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

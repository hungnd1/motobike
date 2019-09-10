<?php

use yii\db\Migration;

class m190910_074309_alter_table_report extends Migration
{
    public function up()
    {
        $this->addColumn('report_form_analyst','form_id',$this->integer(11));
    }

    public function down()
    {
        echo "m190910_074309_alter_table_report cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m180201_040953_add_new_column_ios_report extends Migration
{
    public function up()
    {
        $this->addColumn('report_subscriber_activity','via_ios',$this->integer(11)->defaultValue(0));
    }

    public function down()
    {
        echo "m180201_040953_add_new_column_ios_report cannot be reverted.\n";

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

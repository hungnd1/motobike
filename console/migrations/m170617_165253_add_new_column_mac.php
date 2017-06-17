<?php

use yii\db\Migration;

class m170617_165253_add_new_column_mac extends Migration
{
    public function up()
    {
        $this->addColumn('device_info','mac','varchar(500)');
    }

    public function down()
    {
        echo "m170617_165253_add_new_column_mac cannot be reverted.\n";

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

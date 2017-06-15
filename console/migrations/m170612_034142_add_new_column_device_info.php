<?php

use yii\db\Migration;

class m170612_034142_add_new_column_device_info extends Migration
{
    public function up()
    {
        $this->addColumn('device_info','status','int');
    }

    public function down()
    {
        echo "m170612_034142_add_new_column_device_info cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m180511_114330_add_new_column_subscriber extends Migration
{
    public function up()
    {
        $this->addColumn('subscriber','coin',$this->integer(11));
    }

    public function down()
    {
        echo "m180511_114330_add_new_column_subscriber cannot be reverted.\n";

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

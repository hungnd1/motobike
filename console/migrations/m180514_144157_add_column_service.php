<?php

use yii\db\Migration;

class m180514_144157_add_column_service extends Migration
{
    public function up()
    {
        $this->addColumn('service','order',$this->integer(11));
    }

    public function down()
    {
        echo "m180514_144157_add_column_service cannot be reverted.\n";

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

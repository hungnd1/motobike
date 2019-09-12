<?php

use yii\db\Migration;

class m190912_174220_add_column_last_login extends Migration
{
    public function up()
    {
        $this->addColumn('subscriber','farmer_id',$this->integer(11));
    }

    public function down()
    {
        echo "m190912_174220_add_column_last_login cannot be reverted.\n";

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

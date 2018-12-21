<?php

use yii\db\Migration;

class m181221_102352_alter_table_rating extends Migration
{
    public function up()
    {
        $this->addColumn('rating','type',$this->integer(11));
    }

    public function down()
    {
        echo "m181221_102352_alter_table_rating cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m171116_030956_alter_table_news extends Migration
{
    public function up()
    {
        $this->addColumn('news','order',$this->integer(11));
    }

    public function down()
    {
        echo "m171116_030956_alter_table_news cannot be reverted.\n";

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

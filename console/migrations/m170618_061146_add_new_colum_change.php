<?php

use yii\db\Migration;

class m170618_061146_add_new_colum_change extends Migration
{
    public function up()
    {
        $this->addColumn('price_coffee','change_info','varchar(100)');
    }

    public function down()
    {
        echo "m170618_061146_add_new_colum_change cannot be reverted.\n";

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

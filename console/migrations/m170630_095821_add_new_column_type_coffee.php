<?php

use yii\db\Migration;

class m170630_095821_add_new_column_type_coffee extends Migration
{
    public function up()
    {
        $this->addColumn('exchange_buy','type_coffee_id','int(11)');
    }

    public function down()
    {
        echo "m170630_095821_add_new_column_type_coffee cannot be reverted.\n";

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

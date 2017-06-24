<?php

use yii\db\Migration;

class m170624_150351_add_column_price extends Migration
{
    public function up()
    {
        $this->addColumn('exchange','price','int(11)');
    }

    public function down()
    {
        echo "m170624_150351_add_column_price cannot be reverted.\n";

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

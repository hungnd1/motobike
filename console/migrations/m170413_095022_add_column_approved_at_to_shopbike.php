<?php

use yii\db\Migration;

class m170413_095022_add_column_approved_at_to_shopbike extends Migration
{
    public function up()
    {
        $this->addColumn('shopbike','approved_at','int');
    }

    public function down()
    {
        echo "m170413_095022_add_column_approved_at_to_shopbike cannot be reverted.\n";

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

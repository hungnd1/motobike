<?php

use yii\db\Migration;

class m170725_134431_add_new_column_category_id extends Migration
{
    public function up()
    {
        $this->addColumn('news','category_id','int(11)');
    }

    public function down()
    {
        echo "m170725_134431_add_new_column_category_id cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m190521_173741_add_new_column_mt_template extends Migration
{
    public function up()
    {
        $this->addColumn('mt_template','station_code',$this->string(255));
    }

    public function down()
    {
        echo "m190521_173741_add_new_column_mt_template cannot be reverted.\n";

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

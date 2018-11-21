<?php

use yii\db\Migration;

class m181120_092729_add_new_column_category_gap_general extends Migration
{
    public function up()
    {
        $this->addColumn('gap_general','category_id',$this->integer(11));
    }

    public function down()
    {
        echo "m181120_092729_add_new_column_category_gap_general cannot be reverted.\n";

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

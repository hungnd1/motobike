<?php

use yii\db\Migration;

class m171102_153314_create_new_column_gap_ganeral extends Migration
{
    public function up()
    {
        $this->addColumn('gap_general','content_2',$this->text());
        $this->addColumn('gap_general','content_3',$this->text());
        $this->addColumn('gap_general','content_4',$this->text());
        $this->addColumn('gap_general','content_5',$this->text());
        $this->addColumn('gap_general','content_6',$this->text());
        $this->addColumn('gap_general','content_7',$this->text());
        $this->addColumn('gap_general','content_8',$this->text());
        $this->addColumn('gap_general','content_9',$this->text());
    }

    public function down()
    {
        echo "m171102_153314_create_new_column_gap_ganeral cannot be reverted.\n";

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

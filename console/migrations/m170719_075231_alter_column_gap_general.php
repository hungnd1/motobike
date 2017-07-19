<?php

use yii\db\Migration;

class m170719_075231_alter_column_gap_general extends Migration
{
    public function up()
    {
        $this->addColumn('gap_general','type','integer(11) ');
        $this->addColumn('gap_general','temperature_max','float(3)');
        $this->addColumn('gap_general','temperature_min','float(3) ');
        $this->addColumn('gap_general','evaporation','float(3) ');
        $this->addColumn('gap_general','humidity','float(3) ');
    }

    public function down()
    {
        echo "m170719_075231_alter_column_gap_general cannot be reverted.\n";

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

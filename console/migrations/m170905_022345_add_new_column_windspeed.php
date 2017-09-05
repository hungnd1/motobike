<?php

use yii\db\Migration;

class m170905_022345_add_new_column_windspeed extends Migration
{
    public function up()
    {
        $this->dropColumn('gap_general','evaporation');
        $this->dropColumn('gap_general','humidity');
        $this->addColumn('gap_general','precipitation_max','float(3)');
        $this->addColumn('gap_general','precipitation_min','float(3)');
        $this->addColumn('gap_general','windspeed_max','float(3)');
        $this->addColumn('gap_general','windspeed_min','float(3)');
    }

    public function down()
    {
        echo "m170905_022345_add_new_column_windspeed cannot be reverted.\n";

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

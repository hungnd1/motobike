<?php

use yii\db\Migration;

class m171007_152259_alter_coluom extends Migration
{
    public function up()
    {
        $this->alterColumn('weather_detail','hprcp','float');
    }

    public function down()
    {
        echo "m171007_152259_alter_coluom cannot be reverted.\n";

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

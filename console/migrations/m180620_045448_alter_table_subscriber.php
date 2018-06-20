<?php

use yii\db\Migration;

class m180620_045448_alter_table_subscriber extends Migration
{
    public function up()
    {
        $this->addColumn('subscriber','weather_detail_id', $this->integer(11));
    }

    public function down()
    {
        echo "m180620_045448_alter_table_subscriber cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170727_142935_alter_column_station_code extends Migration
{
    public function up()
    {
        $this->alterColumn('station','station_code','varchar(255)');
    }

    public function down()
    {
        echo "m170727_142935_alter_column_station_code cannot be reverted.\n";

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

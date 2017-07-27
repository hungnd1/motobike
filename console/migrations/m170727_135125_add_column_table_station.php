<?php

use yii\db\Migration;

class m170727_135125_add_column_table_station extends Migration
{
    public function up()
    {
        $this->addColumn('station','district_code','int(11)');
        $this->addColumn('station','com_code','int(11)');
        $this->addColumn('station','district_name','varchar(255)');
    }

    public function down()
    {
        echo "m170727_135125_add_column_table_station cannot be reverted.\n";

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

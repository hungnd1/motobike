<?php

use yii\db\Migration;

class m170623_171532_add_column_address extends Migration
{
    public function up()
    {
        $this->addColumn('subscriber','address','varchar(500)');
    }

    public function down()
    {
        echo "m170623_171532_add_column_address cannot be reverted.\n";

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

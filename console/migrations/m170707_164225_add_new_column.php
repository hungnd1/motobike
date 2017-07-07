<?php

use yii\db\Migration;

class m170707_164225_add_new_column extends Migration
{
    public function up()
    {
        $this->addColumn('exchange','location_name','varchar(255) default null');
        $this->addColumn('exchange_buy','location_name','varchar(255) default null');
        $this->addColumn('exchange_buy','location','varchar(255) default null');
    }

    public function down()
    {
        echo "m170707_164225_add_new_column cannot be reverted.\n";

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

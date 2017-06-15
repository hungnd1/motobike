<?php

use yii\db\Migration;

class m170615_062833_add_new_column extends Migration
{
    public function up()
    {
        $this->addColumn('news','image','varchar(500)');
    }

    public function down()
    {
        echo "m170615_062833_add_new_column cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170630_180451_add_new_column extends Migration
{
    public function up()
    {
        $this->addColumn('gap_general','title','varchar(500)');
    }

    public function down()
    {
        echo "m170630_180451_add_new_column cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170612_093831_add_new_column_status_statin extends Migration
{
    public function up()
    {
        $this->addColumn('station','status','int');
    }

    public function down()
    {
        echo "m170612_093831_add_new_column_status_statin cannot be reverted.\n";

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

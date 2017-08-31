<?php

use yii\db\Migration;

class m170831_110528_add_new_column_id_gap_comment extends Migration
{
    public function up()
    {
        $this->addColumn('comment','id_disease','int(11)');
    }

    public function down()
    {
        echo "m170831_110528_add_new_column_id_gap_comment cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170831_105554_add_new_column_image_gap_general extends Migration
{
    public function up()
    {
        $this->addColumn('gap_general', 'image', 'varchar(250)');
    }

    public function down()
    {
        echo "m170831_105554_add_new_column_image_gap_general cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m180630_125045_alter_table_group_image extends Migration
{
    public function up()
    {
        $this->addColumn('group', 'image', $this->string(255));
    }

    public function down()
    {
        echo "m180630_125045_alter_table_group_image cannot be reverted.\n";

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

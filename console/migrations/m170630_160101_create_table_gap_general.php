<?php

use yii\db\Migration;

class m170630_160101_create_table_gap_general extends Migration
{
    public function up()
    {
        $this->createTable('gap_general',[
            'id' => $this->primaryKey(),
            'gap' => $this->text(),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        echo "m170630_160101_create_table_gap_general cannot be reverted.\n";

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

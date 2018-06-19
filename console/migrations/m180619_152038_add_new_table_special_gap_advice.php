<?php

use yii\db\Migration;

class m180619_152038_add_new_table_special_gap_advice extends Migration
{
    public function up()
    {
        $this->createTable('special_gap_advice', [
            'id' => $this->primaryKey(11),
            'tag' => $this->string(255),
            'is_question' => $this->integer(11),
            'fruit_id' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'status' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m180619_152038_add_new_table_special_gap_advice cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m181221_064214_create_new_table_rating extends Migration
{
    public function up()
    {
        $this->createTable('rating', [
            'id' => $this->primaryKey(11),
            'rate' => $this->integer(11),
            'content' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'subscriber_id' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m181221_064214_create_new_table_rating cannot be reverted.\n";

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

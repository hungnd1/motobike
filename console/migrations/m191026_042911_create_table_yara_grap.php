<?php

use yii\db\Migration;

class m191026_042911_create_table_yara_grap extends Migration
{
    public function up()
    {
        $this->createTable('yara_gap', [
            'id' => $this->primaryKey(11),
            'title' => $this->string(255),
            'short_description' => $this->string(500),
            'content' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'status' => $this->integer(11),
            'image' => $this->string(500),
            'order' => $this->integer(11),
            'fruit_id' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m191026_042911_create_table_yara_grap cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m191025_042206_create_new_table_yara_supplier extends Migration
{
    public function up()
    {
        $this->createTable('yara_supplier', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(255),
            'address' => $this->string(500),
            'image' => $this->string(500),
            'content' => $this->text(),
            'status' => $this->integer(11),
            'longitude' => $this->string(10),
            'latitude' => $this->string(10),
            'description' => $this->text()
        ]);
    }

    public function down()
    {
        echo "m191025_042206_create_new_table_yara_supplier cannot be reverted.\n";

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

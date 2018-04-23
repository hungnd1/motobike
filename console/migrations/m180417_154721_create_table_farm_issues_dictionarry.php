<?php

use yii\db\Migration;

class m180417_154721_create_table_farm_issues_dictionarry extends Migration
{
    public function up()
    {
        $this->createTable('fruit', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(255),
            'image' => $this->string(255)
        ]);
        $this->createTable('group', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(255),
        ]);
        $this->createTable('feature', [
            'id' => $this->primaryKey(11),
            'display_name' => $this->string(255),
        ]);
        $this->createTable('detail', [
            'id' => $this->primaryKey(11),
            'display_name' => $this->string(255),
            'description' => $this->text(),
            'reason' => $this->text(),
            'harm' => $this->text(),
            'prevention' => $this->text(),
            'feature_id' => $this->integer(11),
            'group_id' => $this->integer(11),
            'fruit_id' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'image' => $this->string(500),
            'status' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m180417_154721_create_table_farm_issues_dictionarry cannot be reverted.\n";

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

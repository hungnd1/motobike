<?php

use yii\db\Migration;

class m190223_180335_create_table_game_mini extends Migration
{
    public function up()
    {
        $this->createTable('game_mini', [
            'id' => $this->primaryKey(11),
            'question' => $this->string(255),
            "answer_a" => $this->string(255),
            "answer_b" => $this->string(255),
            "answer_c" => $this->string(255),
            "answer_d" => $this->string(255),
            "answer_correct" => $this->string(255),
            "created_at" => $this->integer(11),
            'updated_at' => $this->integer(11),
            'status' => $this->integer(11),
            "category_id" => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m190223_180335_create_table_game_mini cannot be reverted.\n";

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

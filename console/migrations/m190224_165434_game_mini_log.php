<?php

use yii\db\Migration;

class m190224_165434_game_mini_log extends Migration
{
    public function up()
    {
        $this->createTable('game_mini_log',[
            'id' => $this->primaryKey(11),
            'subscriber_id' => $this->integer(11),
            'answer' => $this->string(255),
            'correct'=> $this->integer(11),
            'created_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        echo "m190224_165434_game_mini_log cannot be reverted.\n";

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

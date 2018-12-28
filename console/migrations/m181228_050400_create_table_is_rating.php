<?php

use yii\db\Migration;

class m181228_050400_create_table_is_rating extends Migration
{
    public function up()
    {
        $this->createTable('is_rating', [
            'id' => $this->primaryKey(11),
            'type' => $this->integer(11),
            'subscriber_id' => $this->integer(11),
            'created_at' => $this->integer(11),
            'status' => $this->integer(11)

        ]);
    }

    public function down()
    {
        echo "m181228_050400_create_table_is_rating cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m180514_134310_create_new_table_map_subscriber extends Migration
{
    public function up()
    {
        $this->createTable('device_subscriber_asm', [
            'id' => $this->primaryKey(11),
            'device_id' => $this->integer(11),
            'subscriber_id' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m180514_134310_create_new_table_map_subscriber cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170906_032547_create_table_version extends Migration
{
    public function up()
    {
        $this->createTable('version', [
            'id' => $this->primaryKey(),
            'type' => $this->integer(11),
            'version' => $this->string(255),
            'link' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m170906_032547_create_table_version cannot be reverted.\n";

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

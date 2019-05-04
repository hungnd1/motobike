<?php

use yii\db\Migration;

class m190504_182113_appParam extends Migration
{
    public function up()
    {
        $this->createTable('app_param', [
            'id' => $this->primaryKey(11),
            'param_key' => $this->string(255),
            'param_value' => $this->text(),
            'content' => $this->text(),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m190504_182113_appParam cannot be reverted.\n";

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

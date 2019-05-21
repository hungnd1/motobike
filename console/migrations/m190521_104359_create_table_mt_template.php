<?php

use yii\db\Migration;

class m190521_104359_create_table_mt_template extends Migration
{
    public function up()
    {
        $this->createTable('mt_template', [
            'id' => $this->primaryKey(11),
            'mo_key' => $this->string(255),
            'content' => $this->text(),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
        $this->addColumn('mo_mt','mt_template_id',$this->integer(11));
    }

    public function down()
    {
        echo "m190521_104359_create_table_mt_template cannot be reverted.\n";

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

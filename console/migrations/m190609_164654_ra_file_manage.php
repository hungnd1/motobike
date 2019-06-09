<?php

use yii\db\Migration;

class m190609_164654_ra_file_manage extends Migration
{
    public function up()
    {
        $this->createTable('file_manage',[
            'id' => $this->primaryKey(11),
            'display_name' => $this->string(255),
            'category_id' => $this->integer(11),
            'type' => $this->integer(11),
            'file' => $this->text(),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'type_extension' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m190609_164654_ra_file_manage cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m191010_170822_ra_film_document extends Migration
{
    public function up()
    {
        $this->createTable('ra_film_document',[
           'id' => $this->primaryKey(11),
           'title' => $this->string(200),
            'description' => $this->string(500),
            'url' => $this->string(500),
            'fruit_id' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'status' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m191010_170822_ra_film_document cannot be reverted.\n";

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

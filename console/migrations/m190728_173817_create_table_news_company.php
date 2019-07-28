<?php

use yii\db\Migration;

class m190728_173817_create_table_news_company extends Migration
{
    public function up()
    {
        $this->createTable('company_news', [
            'id' => $this->primaryKey(11),
            'title' => $this->string(255),
            'short_description' => $this->text(),
            'content' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'status' => $this->integer(11),
            'image' => $this->string(500),
            'company_id' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m190728_173817_create_table_news_company cannot be reverted.\n";

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

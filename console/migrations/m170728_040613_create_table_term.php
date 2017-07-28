<?php

use yii\db\Migration;

class m170728_040613_create_table_term extends Migration
{
    public function up()
    {
        $this->createTable('term', [
            'id' => $this->primaryKey(),
            'term' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m170728_040613_create_table_term cannot be reverted.\n";

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

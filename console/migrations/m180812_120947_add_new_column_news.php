<?php

use yii\db\Migration;

class m180812_120947_add_new_column_news extends Migration
{
    public function up()
    {
        $this->addColumn('news', 'fruit_id', $this->integer(11));
    }

    public function down()
    {
        echo "m180812_120947_add_new_column_news cannot be reverted.\n";

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

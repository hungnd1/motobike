<?php

use yii\db\Migration;

class m191229_180524_alter_table_term extends Migration
{
    public function up()
    {
        $this->addColumn('term','term_en',$this->text());
    }

    public function down()
    {
        echo "m191229_180524_alter_table_term cannot be reverted.\n";

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

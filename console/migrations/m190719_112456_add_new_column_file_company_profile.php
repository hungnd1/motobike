<?php

use yii\db\Migration;

class m190719_112456_add_new_column_file_company_profile extends Migration
{
    public function up()
    {
        $this->addColumn('company_profile','file',$this->string(255));
    }

    public function down()
    {
        echo "m190719_112456_add_new_column_file_company_profile cannot be reverted.\n";

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

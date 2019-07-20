<?php

use yii\db\Migration;

class m190719_175815_add_new_fileCompanyFile extends Migration
{
    public function up()
    {
        $this->addColumn('company','file_company_file', $this->string(255));
    }

    public function down()
    {
        echo "m190719_175815_add_new_fileCompanyFile cannot be reverted.\n";

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

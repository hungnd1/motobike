<?php

use yii\db\Migration;

class m190719_172752_add_new_ngay_thanh_tra extends Migration
{
    public function up()
    {
        $this->addColumn('company_profile','ngay_thanh_tra',$this->string(255));

    }

    public function down()
    {
        echo "m190719_172752_add_new_ngay_thanh_tra cannot be reverted.\n";

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

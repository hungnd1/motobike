<?php

use yii\db\Migration;

class m190629_113150_add_new_app_param extends Migration
{
    public function up()
    {
        $this->addColumn('app_param','content_en',$this->text());
    }

    public function down()
    {
        echo "m190629_113150_add_new_app_param cannot be reverted.\n";

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

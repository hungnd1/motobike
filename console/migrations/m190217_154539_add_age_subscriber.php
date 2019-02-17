<?php

use yii\db\Migration;

class m190217_154539_add_age_subscriber extends Migration
{
    public function up()
    {
        $this->addColumn('subscriber','age',$this->integer(11));
    }

    public function down()
    {
        echo "m190217_154539_add_age_subscriber cannot be reverted.\n";

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

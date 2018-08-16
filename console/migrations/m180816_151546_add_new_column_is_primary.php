<?php

use yii\db\Migration;

class m180816_151546_add_new_column_is_primary extends Migration
{
    public function up()
    {
        $this->addColumn('fruit','is_primary', $this->integer(11));
        $this->addColumn('fruit','order', $this->integer(11));
    }

    public function down()
    {
        echo "m180816_151546_add_new_column_is_primary cannot be reverted.\n";

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

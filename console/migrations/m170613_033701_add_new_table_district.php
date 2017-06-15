<?php

use yii\db\Migration;

class m170613_033701_add_new_table_district extends Migration
{
    public function up()
    {
        $this->createTable('district', [
            'id' => $this->primaryKey(),
            'province_id' => $this->integer(11),
            'district_name' => $this->string('255')
        ]);
    }

    public function down()
    {
        echo "m170613_033701_add_new_table_district cannot be reverted.\n";

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

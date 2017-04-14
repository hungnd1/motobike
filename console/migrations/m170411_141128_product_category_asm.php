<?php

use yii\db\Migration;

class m170411_141128_product_category_asm extends Migration
{
    public function up()
    {
        $this->createTable('product_category_asm', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'category_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()

        ]);
    }

    public function down()
    {
        echo "m170411_141128_product_category_asm cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170413_075534_create_table_product_shop_bike_asm extends Migration
{
    public function up()
    {
        $sql =<<<SQL
CREATE TABLE `motorbike`.`product_shopbike_asm` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT NOT NULL COMMENT '',
  `shopbike_id` INT NOT NULL COMMENT '',
  `created_at` INT NULL COMMENT '',
  `updated_at` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170413_075534_create_table_product_shop_bike_asm cannot be reverted.\n";

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

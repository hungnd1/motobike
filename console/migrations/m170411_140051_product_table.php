<?php

use yii\db\Migration;

class m170411_140051_product_table extends Migration
{
    public function up()
    {
        $sql =<<<SQL
CREATE TABLE `product` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `display_name` VARCHAR(500) NOT NULL COMMENT '',
  `ascii_name` VARCHAR(500) NULL COMMENT '',
  `code` VARCHAR(45) NOT NULL COMMENT '',
  `type` INT NULL COMMENT '',
  `short_description` VARCHAR(500) NULL COMMENT '',
  `description` TEXT NULL COMMENT '',
  `images` TEXT NULL COMMENT '',
  `status` INT NOT NULL COMMENT '',
  `price` INT NULL DEFAULT 0 COMMENT '',
  `price_promotion` INT NULL DEFAULT 0 COMMENT '',
  `created_at` INT NULL COMMENT '',
  `updated_at` INT NULL COMMENT '',
  `approved_at` INT NULL COMMENT '',
  `like_count` INT NULL COMMENT '',
  `comment_count` INT NULL DEFAULT 0 COMMENT '',
  `is_free` INT NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');


SQL;
        $this->execute($sql);

    }

    public function down()
    {
        echo "m170411_140051_product_table cannot be reverted.\n";

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

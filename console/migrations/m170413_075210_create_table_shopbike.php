<?php

use yii\db\Migration;

class m170413_075210_create_table_shopbike extends Migration
{
    public function up()
    {
        $sql =<<<SQL
CREATE TABLE `shopbike` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `username` VARCHAR(255) NOT NULL COMMENT '',
  `password` VARCHAR(500) NOT NULL COMMENT '',
  `password_hash` VARCHAR(255) NULL COMMENT '',
  `email` VARCHAR(255) NOT NULL COMMENT '',
  `phone` VARCHAR(255) NOT NULL COMMENT '',
  `address` VARCHAR(500) NULL COMMENT '',
  `like_count` INT NULL DEFAULT 0 COMMENT '',
  `rating_count` INT NULL DEFAULT 0 COMMENT '',
  `facebook_id` VARCHAR(500) NULL COMMENT '',
  `avatar` TEXT NULL COMMENT '',
  `status` INT NOT NULL COMMENT '',
  `created_at` INT NULL COMMENT '',
  `updated_at` INT NULL COMMENT '',
  `time_open` INT NULL COMMENT '',
  `time_close` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')


SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170413_075210_create_table_shopbike cannot be reverted.\n";

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

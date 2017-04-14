<?php

use yii\db\Migration;

class m170411_040211_add_category_table extends Migration
{
    public function up()
    {
        $sql =<<<SQL
CREATE TABLE `category` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `display_name` VARCHAR(255) NOT NULL COMMENT '',
  `type` INT NULL COMMENT '',
  `status` INT NOT NULL COMMENT '',
  `description` TEXT NULL COMMENT '',
  `parent_id` INT NULL COMMENT '',
  `order_number` INT NULL COMMENT '',
  `path` VARCHAR(255) NULL COMMENT '',
  `level` INT NULL COMMENT '',
  `child_count` INT NULL COMMENT '',
  `created_at` INT NULL COMMENT '',
  `updated_at` INT NULL COMMENT '',
  `updated_by` INT NULL COMMENT '',
  `created_by` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170411_040211_add_category_table cannot be reverted.\n";

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

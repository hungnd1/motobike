<?php

use yii\db\Migration;

class m170724_134353_create_new_table_category_news extends Migration
{
    public function up()
    {
        $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `category` (
  `id` INT(11) AUTO_INCREMENT NOT NULL,
  `display_name` VARCHAR(200) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `status` INT(11) NOT NULL DEFAULT '1' COMMENT '10 - active\n0 - inactive\n3 - for test only',
  `order_number` INT(11) NOT NULL DEFAULT '0' COMMENT 'dung de sap xep category theo thu tu xac dinh, order chi dc so sanh khi cac category co cung level',
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_name` (`display_name` ASC),
  INDEX `idx_desc` (`description`(255) ASC),
  INDEX `idx_order_no` (`order_number` ASC)
  )
ENGINE = InnoDB
AUTO_INCREMENT = 29;

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170724_134353_create_new_table_category_news cannot be reverted.\n";

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

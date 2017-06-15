<?php

use yii\db\Migration;

class m170614_151923_add_new_table_news extends Migration
{
    public function up()
    {
        $sql=<<<SQL
    CREATE TABLE `news` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `title` VARCHAR(500) NOT NULL COMMENT '',
  `short_description` VARCHAR(500) NULL COMMENT '',
  `description` TEXT NULL COMMENT '',
  `content` TEXT NULL COMMENT '',
  `created_at` INT NULL COMMENT '',
  `updated_at` INT NULL COMMENT '',
  `status` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');

SQL;
    $this->execute($sql);
    }

    public function down()
    {
        echo "m170614_151923_add_new_table_news cannot be reverted.\n";

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

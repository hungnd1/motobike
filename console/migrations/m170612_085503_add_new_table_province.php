<?php

use yii\db\Migration;

class m170612_085503_add_new_table_province extends Migration
{
    public function up()
    {
        $sql =<<<SQL
    CREATE TABLE `province` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `province_name` VARCHAR(255) NULL COMMENT '',
  `province_code` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170612_085503_add_new_table_province cannot be reverted.\n";

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

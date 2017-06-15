<?php

use yii\db\Migration;

class m170611_160749_add_new_table_device_info extends Migration
{
    public function up()
    {
        $sql =<<<SQL
          CREATE TABLE `device_info` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `device_type` INT NOT NULL COMMENT '',
  `device_uid` VARCHAR(500) NOT NULL COMMENT '',
  `created_at` INT NULL COMMENT '',
  `updated_at` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')

SQL;
        $this->execute($sql);

    }

    public function down()
    {
        echo "m170611_160749_add_new_table_device_info cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170729_035756_create_new_table_weather extends Migration
{
    public function up()
    {
        $sql = <<<SQL
      CREATE TABLE `weather_detail` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `station_code` VARCHAR(45) NULL COMMENT '',
  `precipitation` INT NULL COMMENT 'luong mua : mm',
  `tmax` INT NULL COMMENT 'nhiet do cao nhat: do c',
  `tmin` INT NULL COMMENT 'nhiet do thap nha: do c',
  `wnddir` INT NULL COMMENT 'huong gio don vi do : degree',
  `wndspd` INT NULL COMMENT 'toc do gio: m/s',
  `station_id` INT NULL COMMENT '',
  `timestamp` INT NULL COMMENT '',
  `created_at` INT NULL COMMENT '',
  `updated_at` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');


SQL;
        $this->execute($sql);


    }

    public function down()
    {
        echo "m170729_035756_create_new_table_weather cannot be reverted.\n";

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

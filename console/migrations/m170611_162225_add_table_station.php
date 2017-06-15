<?php

use yii\db\Migration;

class m170611_162225_add_table_station extends Migration
{
    public function up()
    {

        $sql =<<<SQL

    CREATE TABLE `station` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `station_code` INT NULL COMMENT '',
  `station_name` VARCHAR(255) NULL COMMENT '',
  `province_id` INT NULL COMMENT '',
  `url_weather` VARCHAR(500) NULL COMMENT '',
  `latitude` VARCHAR(45) NULL COMMENT '',
  `longtitude` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');


SQL;

        $this->execute($sql);

    }

    public function down()
    {
        echo "m170611_162225_add_table_station cannot be reverted.\n";

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

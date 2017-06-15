<?php

use yii\db\Migration;

class m170613_042922_add_new_table_weather extends Migration
{
    public function up()
    {
        $sql =<<<SQL
CREATE TABLE `weatherstationasm` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `temp` INT NULL COMMENT '',
  `humidity` INT NULL COMMENT '',
  `bleeding` INT NULL COMMENT '',
  `rainfall` INT NULL COMMENT '',
  `created_at` INT NULL COMMENT '',
  `station_id` INT  COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');

SQL;
    $this->execute($sql);
    }

    public function down()
    {
        echo "m170613_042922_add_new_table_weather cannot be reverted.\n";

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

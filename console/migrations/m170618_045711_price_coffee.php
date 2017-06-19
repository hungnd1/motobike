<?php

use yii\db\Migration;

class m170618_045711_price_coffee extends Migration
{
    public function up()
    {
        $sql=<<<SQL
    CREATE TABLE `price_coffee` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `province_id` INT NULL COMMENT '',
  `district_id` INT NOT NULL COMMENT '',
  `price_average` INT NULL COMMENT '',
  `unit` INT NULL COMMENT '',
  `created_at` INT NULL COMMENT '',
  `updated_at` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');

SQL;
    $this->execute($sql);
    }

    public function down()
    {
        echo "m170618_045711_price_coffee cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m170618_045711_price_coffee extends Migration
{
    public function up()
    {
        $sql=<<<SQL
    CREATE TABLE `price_coffee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price_average` int(11) DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `last_time_value` int(11) DEFAULT NULL,
  `coffee_old_id` int(11) DEFAULT NULL,
  `organisation_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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

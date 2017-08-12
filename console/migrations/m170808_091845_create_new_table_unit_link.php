<?php

use yii\db\Migration;

class m170808_091845_create_new_table_unit_link extends Migration
{
    public function up()
    {
        $sql = <<<SQl
CREATE TABLE `unit_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SQl;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170808_091845_create_new_table_unit_link cannot be reverted.\n";

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

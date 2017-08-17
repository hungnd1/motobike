<?php

use yii\db\Migration;

class m170817_085128_create_table_comment extends Migration
{
    public function up()
    {
        $sql = <<<SQL
        CREATE TABLE `comment` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_new` int(11) DEFAULT NULL,
  `content` varchar(500) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170817_085128_create_table_comment cannot be reverted.\n";

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

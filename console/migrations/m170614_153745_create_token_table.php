<?php

use yii\db\Migration;

/**
 * Handles the creation of table `token`.
 */
class m170614_153745_create_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql=<<<SQL
    CREATE TABLE `subscriber_token` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `subscriber_id` INT NOT NULL COMMENT '',
  `token` VARCHAR(500) NULL COMMENT '',
  `type` INT NULL COMMENT '',
  `created_at` INT NULL COMMENT '',
  `status` INT NULL COMMENT '',
  `expired_at` INT NULL COMMENT '',
  `channel` INT NULL COMMENT '',
  `updated_at` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');

SQL;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('token');
    }
}

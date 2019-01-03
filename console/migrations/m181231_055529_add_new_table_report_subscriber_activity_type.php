<?php

use yii\db\Migration;

class m181231_055529_add_new_table_report_subscriber_activity_type extends Migration
{
    public function up()
    {
        $this->createTable('subscriber_activity_type',[
           'id' => $this->primaryKey(11),
            'report_date' => $this->integer(11),
            'weather' => $this->integer(11),
            'price' => $this->integer(11),
            'gap' => $this->integer(11),
            'buy' => $this->integer(11),
            'gap_disease' => $this->integer(11),
            'qa' => $this->integer(11),
            'tracuusuco' => $this->integer(11),
            'nongnghiepthongminh' => $this->integer(11),
            'biendoikhihau' => $this->integer(11),
            'tuvansudungphanbon' => $this->integer(11),
        ]);
    }

    public function down()
    {
        echo "m181231_055529_add_new_table_report_subscriber_activity_type cannot be reverted.\n";

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

<?php

use yii\db\Migration;

class m171022_153348_create_table_report_activity extends Migration
{
    public function up()
    {
        $this->createTable('report_subscriber_activity',[
            'id'=>$this->primaryKey(),
            'report_date'=>$this->integer(11),
            'via_site_daily'=>$this->integer(11),
            'total_via_site'=>$this->integer(11),
            'via_android'=>$this->integer(11),
            'via_website'=>$this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m171022_153348_create_table_report_activity cannot be reverted.\n";

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

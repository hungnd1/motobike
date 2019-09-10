<?php

use yii\db\Migration;

class m190910_080628_alter_report_form extends Migration
{
    public function up()
    {
        $this->addColumn('report_form_analyst','nhanCongPhanTram',$this->string(255));
        $this->addColumn('report_form_analyst','phanBonPhanTram',$this->string(255));
        $this->addColumn('report_form_analyst','tuoiPhanTram',$this->string(255));
        $this->addColumn('report_form_analyst','bvtvPhanTram',$this->string(255));
        $this->addColumn('report_form_analyst','chiKhacPhanTram',$this->string(255));
        $this->addColumn('report_form_analyst','giaBan',$this->string(255));
        $this->addColumn('report_form_analyst','loiNhuan',$this->string(255));
        $this->addColumn('report_form_analyst','tongLoiNhuan',$this->string(255));

    }

    public function down()
    {
        echo "m190910_080628_alter_report_form cannot be reverted.\n";

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

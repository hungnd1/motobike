<?php

use yii\db\Migration;

class m170922_041420_banner extends Migration
{
    public function up()
    {
        $this->createTable('banner',[
           'id'=>$this->primaryKey(),
            'banner'=> $this->string(255)
        ]);
    }

    public function down()
    {
        echo "m170922_041420_banner cannot be reverted.\n";

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

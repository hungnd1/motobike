<?php

use yii\db\Migration;

class m171108_025642_matrix_fertilizing extends Migration
{
    public function up()
    {
        $this->createTable('matrix_fertilizing',[
            'id'=>$this->primaryKey(),
            'id_answer_1'=>$this->integer(11),
            'id_answer_2'=>$this->integer(11),
            'content'=>$this->text()
        ]);
    }

    public function down()
    {
        echo "m171108_025642_matrix_fertilizing cannot be reverted.\n";

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

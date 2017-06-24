<?php

use yii\db\Migration;

class m170624_144455_totalquality extends Migration
{
    public function up()
    {
        $this->createTable('total_quality', [
            'id' => $this->primaryKey(),
            'min_total_quality' => $this->double(1),
            'max_total_quality' => $this->double(1),
        ]);
        $this->createTable('sold', [
            'id' => $this->primaryKey(),
            'min_sold' => $this->double(1),
            'max_sold' => $this->double(1)
        ]);
        $this->createTable('type_coffee', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)
        ]);
        $this->createTable('exchange', [
            'id' => $this->primaryKey(),
            'subscriber_id' => $this->integer(11),
            'total_quality_id' => $this->integer(11),
            'sold_id' => $this->integer(11),
            'type_coffee' => $this->integer(11),
            'location' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);
    }

    public function down()
    {
        echo "m170624_144455_totalquality cannot be reverted.\n";

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

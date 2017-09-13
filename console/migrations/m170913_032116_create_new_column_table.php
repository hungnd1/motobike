<?php

use yii\db\Migration;

/**
 * Handles the creation of table `new_column`.
 */
class m170913_032116_create_new_column_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('weather_detail','clouddc','integer(11)');
        $this->addColumn('weather_detail','hprcp','integer(11)');
        $this->addColumn('weather_detail','hsun','float(2)');
        $this->addColumn('weather_detail','RFTMAX','integer(6)');
        $this->addColumn('weather_detail','RFTMIN','integer(6)');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('new_column');
    }
}

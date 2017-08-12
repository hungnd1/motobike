<?php

use yii\db\Migration;

class m170808_085440_add_new_column_new_web extends Migration
{
    public function up()
    {
        $this->addColumn('news','video_url','varchar(500)');
        $this->addColumn('news','view_count','int(11)');
        $this->addColumn('news','like_count','int(11)');
        $this->addColumn('news','comment_count','int(11)');
        $this->addColumn('news','is_slide','int(11)');
    }

    public function down()
    {
        echo "m170808_085440_add_new_column_new_web cannot be reverted.\n";

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

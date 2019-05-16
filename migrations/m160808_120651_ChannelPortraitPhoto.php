<?php

use yii\db\Migration;

class m160808_120651_ChannelPortraitPhoto extends Migration
{
    public function up()
    {
        $this->addColumn('Channel', 'hasPortraitPhoto', 'bool not null default 0 after hasPhoto');
    }

    public function down()
    {
        $this->dropColumn('Channel', 'hasPortraitPhoto');
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

<?php

use yii\db\Migration;

class m160718_064102_AddSortTable extends Migration
{
    public function up()
    {
        $this->createTable('DiscoverChannel', array(
            'channelId' => 'int(10) unsigned NOT NULL',
            'sort' => 'int(10) unsigned NOT NULL',
            'PRIMARY KEY (channelId)',
        ));

		$this->addForeignKey('channelIdCS', 'DiscoverChannel', 'channelId', 'Channel', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('channelIdCS', 'DiscoverChannel');
		$this->dropTable('ChannelSort');
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

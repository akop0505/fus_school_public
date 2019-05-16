<?php

use yii\db\Migration;

class m160831_093337_ChannelFeaturedTable extends Migration
{
    public function up()
    {
        $this->createTable('FeaturedChannel', array(
            'channelId' => 'int(10) unsigned NOT NULL',
            'sort' => 'int(10) unsigned NOT NULL',
            'numPost' => 'int(10) unsigned NOT NULL',
            'PRIMARY KEY (channelId)',
        ));

        $this->addForeignKey('channelIdFeaturedChannel', 'FeaturedChannel', 'channelId', 'Channel', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('channelIdFeaturedChannel', 'FeaturedChannel');
        $this->dropTable('FeaturedChannel');
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

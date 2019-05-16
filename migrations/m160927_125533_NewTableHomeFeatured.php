<?php

use yii\db\Migration;

class m160927_125533_NewTableHomeFeatured extends Migration
{
    public function up()
    {
		$this->createTable('HomepageFeaturedPost', array(
			'channelId' => 'int(10) unsigned NOT NULL',
			'postId' => 'int(10) unsigned NOT NULL',
			'sort' => 'int(10) unsigned NOT NULL',
			'PRIMARY KEY (channelId, postId)',
			'KEY (channelId, sort)',
			'KEY (postId)'
		));

		$this->addForeignKey('channelIdHomepageFeaturedPost', 'HomepageFeaturedPost', 'channelId', 'Channel', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('postIdHomepageFeaturedPost', 'HomepageFeaturedPost', 'postId', 'Post', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('channelIdHomepageFeaturedPost', 'HomepageFeaturedPost');
		$this->dropForeignKey('postIdHomepageFeaturedPost', 'HomepageFeaturedPost');
		$this->dropTable('HomepageFeaturedPost');
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

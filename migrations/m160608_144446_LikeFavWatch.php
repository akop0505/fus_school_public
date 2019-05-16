<?php

use yii\db\Migration;

class m160608_144446_LikeFavWatch extends Migration
{
	public function up()
	{
		$this->createTable('PostLike', array(
			'postId' => 'int(10) unsigned NOT NULL',
			'createdAt' => 'datetime not null',
			'createdById' => 'int unsigned not null',
			'PRIMARY KEY (postId, createdById)',
			'KEY (createdById)'
		));
		$this->addForeignKey('postIdPostLike', 'PostLike', 'postId', 'Post', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('createdByIdPostLike', 'PostLike', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->createTable('PostFavorite', array(
			'postId' => 'int(10) unsigned NOT NULL',
			'createdAt' => 'datetime not null',
			'createdById' => 'int unsigned not null',
			'PRIMARY KEY (postId, createdById)',
			'KEY (createdById)'
		));
		$this->addForeignKey('postIdPostFavorite', 'PostFavorite', 'postId', 'Post', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('createdByIdPostFavorite', 'PostFavorite', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->createTable('PostLater', array(
			'postId' => 'int(10) unsigned NOT NULL',
			'createdAt' => 'datetime not null',
			'createdById' => 'int unsigned not null',
			'PRIMARY KEY (postId, createdById)',
			'KEY (createdById)'
		));
		$this->addForeignKey('postIdPostLater', 'PostLater', 'postId', 'Post', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('createdByIdPostLater', 'PostLater', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->createTable('ChannelSubscribe', array(
			'channelId' => 'int(10) unsigned NOT NULL',
			'createdAt' => 'datetime not null',
			'createdById' => 'int unsigned not null',
			'PRIMARY KEY (channelId, createdById)',
			'KEY (createdById)'
		));
		$this->addForeignKey('channelIdChannelSubscribe', 'ChannelSubscribe', 'channelId', 'Channel', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('createdByIdChannelSubscribe', 'ChannelSubscribe', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->addColumn('Channel', 'subscribers', 'int unsigned not null default 0 after videos');
	}

	public function down()
	{
		$this->dropColumn('Channel', 'subscribers');
		$this->dropTable('ChannelSubscribe');
		$this->dropTable('PostLater');
		$this->dropTable('PostFavorite');
		$this->dropTable('PostLater');
	}
}

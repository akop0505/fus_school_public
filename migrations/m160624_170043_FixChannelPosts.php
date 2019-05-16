<?php

use yii\db\Migration;

class m160624_170043_FixChannelPosts extends Migration
{
	public function up()
	{
		$this->renameColumn('Channel', 'videos', 'numPosts');
		$this->renameColumn('Channel', 'subscribers', 'numSubscribers');
		$this->dropColumn('Institution', 'posts');
		$this->dropColumn('Institution', 'subscribers');
		$this->renameColumn('Institution', 'likes', 'numLikes');
	}

	public function down()
	{
		$this->renameColumn('Channel', 'numSubscribers', 'subscribers');
		$this->renameColumn('Channel', 'numPosts', 'videos');
		$this->addColumn('Institution', 'posts', 'int unsigned not null default 0');
		$this->renameColumn('Institution', 'numLikes', 'likes');
		$this->addColumn('Institution', 'subscribers', 'int unsigned not null default 0');
	}
}

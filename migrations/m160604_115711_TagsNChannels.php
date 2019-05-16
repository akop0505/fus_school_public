<?php

use yii\db\Migration;

class m160604_115711_TagsNChannels extends Migration
{
    public function up()
    {
		$this->createTable('Channel', array(
			'id' => 'int(10) unsigned NOT NULL auto_increment',
			'name' => 'varchar(255) NOT NULL',
			'hasPhoto' => 'bool NOT NULL default 0',
			'videos' => 'int unsigned NOT NULL default 0',
			'isActive' => 'bool NOT NULL default 1',
			'createdAt' => 'datetime not null',
			'createdById' => 'int unsigned not null',
			'updatedAt' => 'datetime not null',
			'updatedById' => 'int unsigned not null',
			'PRIMARY KEY (id)',
			'UNIQUE (name)',
			'KEY (createdById)',
			'KEY (updatedById)'
		));
		$this->addForeignKey('createdByIdChannel', 'Channel', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('updatedByIdChannel', 'Channel', 'updatedById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->createTable('Tag', array(
			'id' => 'int(10) unsigned NOT NULL auto_increment',
			'name' => 'varchar(255) NOT NULL',
			'isActive' => 'bool NOT NULL default 1',
			'PRIMARY KEY (id)',
			'UNIQUE (name)'
		));
		$this->addColumn('User', 'hasPhoto', 'bool not null default 0 after dateOfBirth');
		$this->addColumn('Institution', 'themeColor', 'char(7) not null default "#e12c3c" after address');
		$this->addColumn('Institution', 'posts', 'int unsigned not null default 0 after themeColor');
		$this->addColumn('Institution', 'likes', 'int unsigned not null default 0 after posts');
		$this->addColumn('Institution', 'subscribers', 'int unsigned not null default 0 after likes');
    }

    public function down()
    {
		$this->dropColumn('Institution', 'subscribers');
		$this->dropColumn('Institution', 'likes');
		$this->dropColumn('Institution', 'posts');
		$this->dropColumn('Institution', 'themeColor');
		$this->dropColumn('User', 'hasPhoto');
		$this->dropTable('Tag');
		$this->dropTable('Channel');
    }
}

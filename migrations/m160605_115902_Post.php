<?php

use yii\db\Migration;

class m160605_115902_Post extends Migration
{
	public function up()
	{
		$this->createTable('Post', array(
			'id' => 'int(10) unsigned NOT NULL auto_increment',
			'title' => 'varchar(255) NOT NULL',
			'postText' => 'longtext NOT NULL',
			'hasHeaderPhoto' => 'bool NOT NULL default 0',
			'hasThumbPhoto' => 'bool NOT NULL default 0',
			'views' => 'int unsigned NOT NULL default 0',
			'isActive' => 'bool NOT NULL default 0',
			'createdAt' => 'datetime not null',
			'createdById' => 'int unsigned not null',
			'updatedAt' => 'datetime not null',
			'updatedById' => 'int unsigned not null',
			'PRIMARY KEY (id)',
			'KEY (createdById)',
			'KEY (updatedById)'
		));
		$this->addForeignKey('createdByIdPost', 'Post', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('updatedByIdPost', 'Post', 'updatedById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->addColumn('Channel', 'description', 'varchar(255) default NULL after name');
		$this->addColumn('Channel', 'institutionId', 'int unsigned default NULL after id');
		$this->createIndex('institutionId', 'Channel', 'institutionId');
		$this->addForeignKey('institutionIdChannel', 'Channel', 'institutionId', 'Institution', 'id', 'RESTRICT', 'RESTRICT');
		$this->createTable('PostTag', array(
			'tagId' => 'int(10) unsigned NOT NULL',
			'postId' => 'int(10) unsigned NOT NULL',
			'createdAt' => 'datetime not null',
			'createdById' => 'int unsigned not null',
			'PRIMARY KEY (tagId, postId)',
			'KEY (postId)',
			'KEY (createdById)'
		));
		$this->addForeignKey('tagIdPostTag', 'PostTag', 'tagId', 'Tag', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('postIdPostTag', 'PostTag', 'postId', 'Post', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('createdByIdPostTag', 'PostTag', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->createTable('PostChannel', array(
			'channelId' => 'int(10) unsigned NOT NULL',
			'postId' => 'int(10) unsigned NOT NULL',
			'createdAt' => 'datetime not null',
			'createdById' => 'int unsigned not null',
			'PRIMARY KEY (channelId, postId)',
			'KEY (postId)',
			'KEY (createdById)'
		));
		$this->addForeignKey('channelIdPostChannel', 'PostChannel', 'channelId', 'Channel', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('postIdPostChannel', 'PostChannel', 'postId', 'Post', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('createdByIdPostChannel', 'PostChannel', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->createTable('InstitutionLike', array(
			'institutionId' => 'int(10) unsigned NOT NULL',
			'createdAt' => 'datetime not null',
			'createdById' => 'int unsigned not null',
			'PRIMARY KEY (institutionId, createdById)',
			'KEY (createdById)'
		));
		$this->addForeignKey('institutionIdInstitutionLike', 'InstitutionLike', 'institutionId', 'Institution', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('createdByIdInstitutionLike', 'InstitutionLike', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
	}

	public function down()
	{
		$this->dropTable('InstitutionLike');
		$this->dropTable('PostChannel');
		$this->dropTable('PostTag');
		$this->dropForeignKey('institutionIdChannel', 'Channel');
		$this->dropColumn('Channel', 'institutionId');
		$this->dropColumn('Channel', 'description');
		$this->dropTable('Post');
	}
}

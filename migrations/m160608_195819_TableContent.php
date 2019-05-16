<?php

use yii\db\Migration;

class m160608_195819_TableContent extends Migration
{
    public function up()
    {
        $this->createTable('Content', array(
            'id' => 'int(10) unsigned NOT NULL auto_increment',
            'urlSlug' => 'varchar(64) COLLATE utf8_unicode_ci NOT NULL',
            'title' => 'varchar(64) COLLATE utf8_unicode_ci NOT NULL',
            'bodyText' => 'text COLLATE utf8_unicode_ci NOT NULL',
            'createdAt' => 'datetime NOT NULL',
            'createdById' => 'int(10) unsigned NOT NULL',
            'updatedAt' => 'datetime NOT NULL',
            'updatedById' => 'int(10) unsigned NOT NULL',
            'PRIMARY KEY (id)',
            'KEY (createdById)',
            'KEY (updatedById)'
        ));

        $this->addForeignKey('createdByIdContent', 'Content', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('updatedByIdContent', 'Content', 'updatedById', 'User', 'id', 'RESTRICT', 'RESTRICT');

		$this->addColumn('User', 'about', 'longtext COLLATE utf8_unicode_ci DEFAULT NULL');
		$this->addColumn('Institution', 'about', 'longtext COLLATE utf8_unicode_ci DEFAULT NULL');

		$this->addColumn('Channel', 'userId', 'int(10) unsigned DEFAULT NULL AFTER institutionId');
		$this->createIndex('userId', 'Channel', 'userId');
		$this->addForeignKey('userIdChannel', 'Channel', 'userId', 'User', 'id', 'RESTRICT', 'RESTRICT');

		$this->addColumn('Channel', 'isSystem', 'tinyint(1) DEFAULT 0 AFTER isActive');

    }

    public function down()
    {
        $this->dropTable('Content');
		$this->dropColumn('User', 'about');
		$this->dropColumn('Institution', 'about');

		$this->dropForeignKey('userIdChannel', 'Channel');
		$this->dropIndex('userId', 'Channel');
		$this->dropColumn('Channel', 'userId');
		$this->dropColumn('Channel', 'isSystem');
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

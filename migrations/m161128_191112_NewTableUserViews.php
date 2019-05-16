<?php

use yii\db\Migration;

class m161128_191112_NewTableUserViews extends Migration
{
    public function up()
    {
		$this->createTable('UserViews', array(
			'id' => 'bigint unsigned NOT NULL auto_increment',
			'viewType' => 'ENUM("Post", "School", "Profile")',
			'viewTypeFk' => 'int(10) unsigned not null',
			'createdAt' => 'datetime not null',
			'createdById' => 'int(10) unsigned default null',
			'PRIMARY KEY (id)',
			'KEY (createdById)'
		));

		$this->addForeignKey('createdByIdUserViews', 'UserViews', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('createdByIdUserViews', 'UserViews');
		$this->dropTable('UserViews');
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

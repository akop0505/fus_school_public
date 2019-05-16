<?php

use yii\db\Migration;

class m170328_091435_AddMediaTable extends Migration
{
    public function up()
    {
		$this->createTable('PostMedia', array(
			'id' => 'int(10) unsigned NOT NULL auto_increment',
			'postId' => 'int(10) unsigned NOT NULL',
			'filename' => 'varchar(64)  NOT NULL',
			'sort' => 'int(10) default 0  NOT NULL',
			'PRIMARY KEY (id)',
			'KEY (postId, sort)'
		));

		$this->addForeignKey('PostMediaPostId', 'PostMedia', 'postId', 'Post', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('PostMediaPostId', 'PostMedia');
		$this->dropTable('PostMedia');
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

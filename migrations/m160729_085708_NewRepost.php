<?php

use yii\db\Migration;

class m160729_085708_NewRepost extends Migration
{
    public function up()
    {
        $this->createTable('PostRepost', array(
            'postId' => 'int(10) unsigned NOT NULL',
            'institutionId' => 'int(10) unsigned NOT NULL',
            'isApproved' => 'bool NOT NULL default 0',
            'createdAt' => 'datetime not null',
            'createdById' => 'int(10) unsigned not null',
            'PRIMARY KEY (postId, institutionId)',
			'KEY (institutionId)',
            'KEY (createdById)'
        ));

        $this->addForeignKey('postIdPostRepost', 'PostRepost', 'postId', 'Post', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('institutionIdPostRepost', 'PostRepost', 'institutionId', 'Institution', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('createdByIdPostRepost', 'PostRepost', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('postIdPostRepost', 'PostRepost');
		$this->dropForeignKey('institutionIdPostRepost', 'PostRepost');
		$this->dropForeignKey('createdByIdPostRepost', 'PostRepost');
		$this->dropTable('PostRepost');
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

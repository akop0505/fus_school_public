<?php

use yii\db\Migration;

class m170425_133658_AddNewToPost extends Migration
{
    public function up()
    {
		$this->addColumn('Post', 'dateToBePublishedSetById', 'int(10) UNSIGNED default null');
		$this->createIndex('dateToBePublishedSetById', 'Post', 'dateToBePublishedSetById');
		$this->addForeignKey('dateToBePublishedSetByIdPost', 'Post', 'dateToBePublishedSetById', 'User', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('dateToBePublishedSetByIdPost', 'Post');
		$this->dropIndex('dateToBePublishedSetById', 'Post');
    	$this->dropColumn('Post', 'dateToBePublishedSetById');
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

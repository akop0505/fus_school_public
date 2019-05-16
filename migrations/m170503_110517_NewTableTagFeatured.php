<?php

use yii\db\Migration;

class m170503_110517_NewTableTagFeatured extends Migration
{
    public function up()
    {
		$this->createTable('TagFeatured', array(
			'institutionId' => 'int(10) unsigned NOT NULL',
			'tagId' => 'int(10) unsigned NOT NULL',
			'sort' => 'int(10) unsigned NOT NULL',
			'PRIMARY KEY (institutionId, sort)',
			'KEY (tagId)'
		));

		$this->addForeignKey('tagIdTagFeatured', 'TagFeatured', 'tagId', 'Tag', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('institutionIdTagFeatured', 'TagFeatured', 'institutionId', 'Institution', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('tagIdTagFeatured', 'TagFeatured');
		$this->dropForeignKey('institutionIdTagFeatured', 'TagFeatured');
		$this->dropTable('TagFeatured');
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

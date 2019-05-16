<?php

use yii\db\Migration;

class m170125_131510_NewStudentsFeaturedTable extends Migration
{
    public function up()
    {
		$this->createTable('StudentsFeatured', array(
			'institutionId' => 'int(10) unsigned NOT NULL',
			'userId' => 'int(10) unsigned NOT NULL',
			'sort' => 'int(10) unsigned NOT NULL',
			'PRIMARY KEY (institutionId, sort)',
			'KEY (userId)'
		));

		$this->addForeignKey('userIdStudentsFeatured', 'StudentsFeatured', 'userId', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('institutionIdStudentsFeatured', 'StudentsFeatured', 'institutionId', 'Institution', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('userIdStudentsFeatured', 'StudentsFeatured');
		$this->dropForeignKey('institutionIdStudentsFeatured', 'StudentsFeatured');
		$this->dropTable('StudentsFeatured');
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

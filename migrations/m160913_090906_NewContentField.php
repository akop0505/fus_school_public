<?php

use yii\db\Migration;

class m160913_090906_NewContentField extends Migration
{
    public function up()
    {
        $this->addColumn('Content', 'extraHtml', 'text default NULL');

        $this->createTable('FileUpload', array(
			'id' => 'int(10) unsigned NOT NULL auto_increment',
            'fileName' => 'varchar(64) NOT NULL',
			'createdAt' => 'datetime not null',
			'createdById' => 'int(10) unsigned not null',
			'PRIMARY KEY (id)',
			'KEY (createdById)'
        ));

		$this->addForeignKey('createdByIdFileUpload', 'FileUpload', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropColumn('Content', 'extraHtml');
		$this->dropForeignKey('createdByIdFileUpload', 'FileUpload');
		$this->dropTable('FileUpload');
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

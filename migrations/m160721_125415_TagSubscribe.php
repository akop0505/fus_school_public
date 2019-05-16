<?php

use yii\db\Migration;

class m160721_125415_TagSubscribe extends Migration
{
    public function up()
    {
        $this->createTable('TagSubscribe', array(
            'tagId' => 'int(10) unsigned NOT NULL',
            'createdAt' => 'datetime not null',
            'createdById' => 'int(10) unsigned not null',
            'PRIMARY KEY (tagId, createdById)',
            'KEY (createdById)'
        ));
        $this->addForeignKey('tagIdTagSubscribe', 'TagSubscribe', 'tagId', 'Tag', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('createdByIdTagSubscribe', 'TagSubscribe', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('tagIdTagSubscribe', 'TagSubscribe');
        $this->dropForeignKey('createdByIdTagSubscribe', 'TagSubscribe');
        $this->dropTable('TagSubscribe');
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

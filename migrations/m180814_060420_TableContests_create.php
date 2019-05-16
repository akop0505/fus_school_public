<?php

use yii\db\Migration;

class m180814_060420_TableContests_create extends Migration
{
    public function up()
    {
        $this->createTable('Contest', array(
            'id' => 'int(10) unsigned NOT NULL auto_increment',
            'title' => 'varchar(255) NOT NULL',
            'content' => 'longtext NOT NULL',
            'hasHeaderPhoto' => 'bool NOT NULL default 0',
            'video' => 'varchar(255) default NULL',
            'type' => 'ENUM("article", "video")',
            'createdById' => 'int unsigned not null',
            'isActive' => 'bool NOT NULL default 0',
            'createdAt' => 'datetime not null',
            'updatedAt' => 'datetime not null',
            'datePublished' => 'datetime default NULL',
            'PRIMARY KEY (id)',
        ));

        $this->addForeignKey('createdByIdFK', 'Post', 'createdById', 'User', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('Contest');
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

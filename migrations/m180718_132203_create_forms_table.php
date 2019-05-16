<?php

use yii\db\Migration;

/**
 * Handles the creation of table `forms`.
 */
class m180718_132203_create_forms_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('Form', array(
            'id' => 'int(10) unsigned NOT NULL auto_increment',
            'first_name' => 'varchar(255) NOT NULL',
            'last_name' => 'varchar(255) NOT NULL',
            'email' => 'varchar(255) NOT NULL',
            'title' => 'varchar(255) NOT NULL',
            'postText' => 'longtext NOT NULL',
            'hasHeaderPhoto' => 'bool NOT NULL default 0',
            'hasThumbPhoto' => 'bool NOT NULL default 0',
            'video' => 'varchar(255) default NULL',
            'type' => 'ENUM("article", "video")',
            'school' => 'varchar(255) NOT NULL',
            'isActive' => 'bool NOT NULL default 0',
            'isApproved' => 'bool NOT NULL default 0',
            'approvedById' => 'int(10) UNSIGNED default NULL',
            'createdAt' => 'datetime not null',
            'updatedAt' => 'datetime not null',
            'datePublished' => 'datetime default NULL',
            'PRIMARY KEY (id)',
            'KEY (approvedById)',
        ));

        $this->addForeignKey('approvedByIdForm', 'Form', 'approvedById', 'User', 'id', 'RESTRICT', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey("approvedByIdForm","Form");
        $this->dropTable('Form');
    }
}

<?php

use yii\db\Migration;

class m160502_131009_SessionUserTable extends Migration
{
    public function up()
    {
        $this->createTable('session', array(
            'id' => 'char(40) COLLATE utf8_unicode_ci NOT NULL',
            'expire' => 'int(11) DEFAULT NULL',
            'data' => 'blob',
            'PRIMARY KEY (id)'
        ));

        $this->createTable('User', array(
            'id' => 'int(10) unsigned NOT NULL',
            'username' => 'varchar(255) COLLATE utf8_unicode_ci NOT NULL',
            'authKey' => 'char(32) COLLATE utf8_unicode_ci NOT NULL',
            'passwordHash' => 'varchar(128) COLLATE utf8_unicode_ci NOT NULL',
            'passwordResetToken' => 'varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL',
            'email' => 'varchar(255) COLLATE utf8_unicode_ci NOT NULL',
            'emailVerified' => 'tinyint(1) NOT NULL DEFAULT 0',
            'status' => 'enum("pending","active","deleted") COLLATE utf8_unicode_ci NOT NULL DEFAULT "pending"',
            'createdAt' => 'datetime NOT NULL',
            'updatedAt' => 'datetime NOT NULL',
            'lastLogin' => 'datetime DEFAULT NULL',
            'firstName' => 'varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL',
            'lastName' => 'varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL',
            'isMale' => 'tinyint(1) NOT NULL DEFAULT 1',
            'dateOfBirth' => 'date DEFAULT NULL',
            'city' => 'varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL',
            'address' => 'varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL',
            'mobilePhone' => 'varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL',
            'countryId' => ' int(10) unsigned NOT NULL DEFAULT 1',
            'PRIMARY KEY (id)',
			'KEY (countryId)',
        ));

        $this->createIndex('username', 'User', 'username', true);
        $this->createIndex('email', 'User', 'email', true);
    }

    public function down()
    {
        $this->dropTable('session');
        $this->dropTable('User');
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

<?php

use yii\db\Migration;
use yii\db\Expression;

class m160610_122350_FixUser extends Migration
{
    public function up()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->alterColumn('User', 'id', 'int(10) unsigned NOT NULL auto_increment');
        $this->execute('SET foreign_key_checks = 1');

        $this->insert('Institution', array(
            'id' => 1,
            'name' => 'FusFoo',
            'cityId' => 50,
            'address' => 'FusFoo',
            'createdById' => 1,
            'updatedById' => 1,
            'createdAt' => new Expression('NOW()'),
            'updatedAt' => new Expression('NOW()'),
        ));

        $this->update('User', array('institutionId' => 1), 'id = 1');
        $this->alterColumn('User', 'institutionId', 'int(10) unsigned NOT NULL');

    }

    public function down()
    {
        $this->alterColumn('User', 'id', 'int(10) unsigned NOT NULL');
        $this->alterColumn('User', 'institutionId', 'int(10) unsigned DEFAULT NULL');
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

<?php

use yii\db\Migration;

class m171020_201130_CreateArchivedStudents extends Migration
{
    public function up()
    {
        $this->createTable('StudentsArchived', array(
            'institutionId' => 'int(10) unsigned NOT NULL',
            'userId' => 'int(10) unsigned NOT NULL',
            'sort' => 'int(10) unsigned NOT NULL',
            'PRIMARY KEY (institutionId, sort)',
            'KEY (userId)'
        ));

        $this->addForeignKey('userStudentsArchived', 'StudentsArchived', 'userId', 'User', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('institutionIdStudentsArchived', 'StudentsArchived', 'institutionId', 'Institution', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        echo "m171020_201130_CreateArchivedStudents cannot be reverted.\n";

        return false;
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

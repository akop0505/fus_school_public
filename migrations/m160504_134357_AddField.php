<?php

use yii\db\Migration;

class m160504_134357_AddField extends Migration
{
    public function up()
    {
        $this->addColumn('User', 'timeZone', 'varchar(64) NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('User', 'timeZone');
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

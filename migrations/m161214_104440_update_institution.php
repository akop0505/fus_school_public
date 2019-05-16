<?php

use yii\db\Migration;

class m161214_104440_update_institution extends Migration
{
    public function up()
    {
        $this->addColumn('Institution', 'fbPageId', 'varchar(30) null');
        $this->addColumn('Institution', 'fbPageToken', 'varchar(255) null');
    }

    public function down()
    {
        $this->dropColumn('Institution', 'fbPageId');
        $this->dropColumn('Institution', 'fbPageToken');
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

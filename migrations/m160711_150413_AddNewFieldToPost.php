<?php

use yii\db\Migration;

class m160711_150413_AddNewFieldToPost extends Migration
{
    public function up()
    {
        $this->addColumn('Post', 'isApproved', 'bool NOT NULL default 0 after isActive');
    }

    public function down()
    {
        $this->dropColumn('Post', 'isApproved');
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

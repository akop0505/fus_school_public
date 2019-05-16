<?php

use yii\db\Migration;

class m160909_093532_NewPostField extends Migration
{
    public function up()
    {
        $this->addColumn('Post', 'isNational', 'bool NOT NULL default 0');
    }

    public function down()
    {
        $this->dropColumn('Post', 'isNational');
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

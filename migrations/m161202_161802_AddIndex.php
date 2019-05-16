<?php

use yii\db\Migration;

class m161202_161802_AddIndex extends Migration
{
    public function up()
    {
		$this->createIndex('createdAt', 'UserViews', 'viewType, createdAt');
    }

    public function down()
    {

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

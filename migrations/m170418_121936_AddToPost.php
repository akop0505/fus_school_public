<?php

use yii\db\Migration;

class m170418_121936_AddToPost extends Migration
{
    public function up()
    {
		$this->addColumn('Post', 'dateToBePublished', 'datetime default null');
    }

    public function down()
    {
		$this->dropColumn('Post', 'dateToBePublished');
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

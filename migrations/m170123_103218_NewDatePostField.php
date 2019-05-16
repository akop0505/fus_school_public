<?php

use yii\db\Migration;

class m170123_103218_NewDatePostField extends Migration
{
    public function up()
    {
		$this->addColumn('Post', 'datePublished', 'date default null');
		$this->execute("UPDATE Post set datePublished = createdAt where datePublished is null;");
    }

    public function down()
    {
		$this->dropColumn('Post', 'datePublished');
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

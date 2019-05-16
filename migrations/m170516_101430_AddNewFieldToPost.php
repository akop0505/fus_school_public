<?php

use yii\db\Migration;

class m170516_101430_AddNewFieldToPost extends Migration
{
    public function up()
    {
		$this->addColumn('Post', 'fullTextContent', 'longtext default null');
		$this->execute("ALTER TABLE Post ADD FULLTEXT INDEX fullTextContent (fullTextContent ASC)");
    }

    public function down()
    {
		$this->dropIndex('fullTextContent', 'Post');
		$this->dropColumn('Post', 'fullTextContent');
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

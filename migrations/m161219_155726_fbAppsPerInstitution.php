<?php

use yii\db\Migration;

class m161219_155726_fbAppsPerInstitution extends Migration
{
    public function up()
    {
		$this->addColumn('Institution', 'fbAppId', 'varchar(30) null');
		$this->addColumn('Institution', 'fbAppSecret', 'varchar(255) null');
    }

    public function down()
    {
		$this->dropColumn('Institution', 'fbAppId');
		$this->dropColumn('Institution', 'fbAppSecret');
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

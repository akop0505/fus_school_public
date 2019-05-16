<?php

use yii\db\Migration;

class m160817_133030_AddInstitutionField extends Migration
{
    public function up()
    {
        $this->addColumn('Institution', 'latestLink', 'varchar(255)	COLLATE utf8_unicode_ci DEFAULT NULL');
    }

    public function down()
    {
        $this->dropColumn('Institution', 'latestLink');
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

<?php

use yii\db\Migration;

class m160920_080848_FixUserInstitutionField extends Migration
{
    public function up()
    {
        $this->alterColumn('User', 'institutionId', 'int(10) UNSIGNED DEFAULT NULL');
    }

    public function down()
    {
        $this->alterColumn('User', 'institutionId', 'int(10) UNSIGNED NOT NULL');
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

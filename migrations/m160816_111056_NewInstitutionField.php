<?php

use yii\db\Migration;

class m160816_111056_NewInstitutionField extends Migration
{
    public function up()
    {
        $this->addColumn('Institution', 'hasLatestPhoto', 'tinyint(1) not null default 0');
    }

    public function down()
    {
        $this->dropColumn('Institution', 'hasLatestPhoto');
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

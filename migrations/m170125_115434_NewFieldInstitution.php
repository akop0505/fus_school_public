<?php

use yii\db\Migration;

class m170125_115434_NewFieldInstitution extends Migration
{
    public function up()
    {
		$this->addColumn('Institution', 'aboutUsLinkColor', 'char(7) not null default "#e12c3c" after themeColor');
    }

    public function down()
    {
		$this->dropColumn('Institution', 'aboutUsLinkColor');
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

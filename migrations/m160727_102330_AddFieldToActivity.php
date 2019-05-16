<?php

use yii\db\Migration;

class m160727_102330_AddFieldToActivity extends Migration
{
    public function up()
    {
        $this->addColumn('UserActivity', 'isRemove', 'bool not null default 0');
    }

    public function down()
    {
        $this->dropColumn('UserActivity', 'isRemove');
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

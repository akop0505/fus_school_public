<?php

use yii\db\Migration;

class m171020_201140_AddAvatarNameToUserTable extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'avatar_name', 'varchar(128) DEFAULT NULL');
    }

    public function down()
    {
        echo "m171020_201140_AddAvatarNameToUserTable cannot be reverted.\n";

        return false;
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

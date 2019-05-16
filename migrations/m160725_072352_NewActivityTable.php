<?php

use yii\db\Migration;

class m160725_072352_NewActivityTable extends Migration
{
    public function up()
    {
        $this->createTable('UserActivity', array(
            'id' => 'bigint unsigned NOT NULL auto_increment',
            'activityType' => 'ENUM("Post", "PostLike", "PostLater", "PostFavorite", "ChannelSubscribe", "TagSubscribe", "InstitutionLike")',
            'activityTypeFk' => 'int(10) unsigned not null',
            'createdAt' => 'datetime not null',
            'createdById' => 'int(10) unsigned not null',
            'PRIMARY KEY (id)',
            'KEY (createdById)'
        ));

        $this->addForeignKey('createdByIdUserActivity', 'UserActivity', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('createdByIdUserActivity', 'UserActivity');
        $this->dropTable('UserActivity');
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

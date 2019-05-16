<?php

use yii\db\Migration;

class m180816_072354_TableContestChannel_create extends Migration
{
    public function up()
    {
        $this->createTable('ContestChannel', array(
            'id' => 'int(10) unsigned NOT NULL auto_increment',
            'channelId' => 'int unsigned not null',
            'contestId'=>'int unsigned not null',
            'createdById'=>'int unsigned not null',
            'createdAt' => 'datetime not null',
            'PRIMARY KEY (id)'
        ));

        $this->addForeignKey('channelIdFK', 'ContestChannel', 'channelId', 'Channel', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('contestIdFK', 'ContestChannel', 'contestId', 'Contest', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('contestChannel_createdByIdFK', 'ContestChannel', 'createdById', 'User', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('ContestChannel');
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

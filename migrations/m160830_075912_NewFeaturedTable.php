<?php

use yii\db\Migration;

class m160830_075912_NewFeaturedTable extends Migration
{
    public function up()
    {
        $this->createTable('PostFeatured', array(
            'institutionId' => 'int(10) unsigned NOT NULL',
            'postId' => 'int(10) unsigned NOT NULL',
            'sort' => 'int(10) unsigned NOT NULL',
            'PRIMARY KEY (institutionId, sort)',
            'KEY (postId)'
        ));

        $this->addForeignKey('postIdPostFeatured', 'PostFeatured', 'postId', 'Post', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('institutionIdPostFeatured', 'PostFeatured', 'institutionId', 'Institution', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('postIdPostFeatured', 'PostFeatured');
        $this->dropForeignKey('institutionIdPostFeatured', 'PostFeatured');
        $this->dropTable('PostFeatured');
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

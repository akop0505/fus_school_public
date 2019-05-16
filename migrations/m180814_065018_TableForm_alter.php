<?php

use yii\db\Migration;

class m180814_065018_TableForm_alter extends Migration
{
    public function up()
    {
        $this->dropColumn('Form','title');
        $this->dropColumn('Form','postText');
        $this->dropColumn('Form','hasHeaderPhoto');
        $this->dropColumn('Form','hasThumbPhoto');
        $this->dropColumn('Form','video');
        $this->dropColumn('Form','type');
        $this->dropColumn('Form','isActive');
        $this->dropColumn('Form','datePublished');

        $this->addColumn('Form','contest_id','int(10) UNSIGNED default NULL');
        $this->addForeignKey("contest_id_fk",'Form','contest_id','Contest','id','CASCADE','CASCADE');
    }

    public function down()
    {
        $this->addColumn('Form','title','varchar(255)');
        $this->addColumn('Form','postText','varchar(255)');
        $this->addColumn('Form','hasHeaderPhoto','varchar(255)');
        $this->addColumn('Form','hasThumbPhoto','varchar(255)');
        $this->addColumn('Form','video','varchar(255)');
        $this->addColumn('Form','type','varchar(255)');
        $this->addColumn('Form','isActive','varchar(255)');
        $this->addColumn('Form','datePublished','varchar(255)');

        $this->dropForeignKey("contest_id_fk","Form");
        $this->dropColumn('Form','contest_id');
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

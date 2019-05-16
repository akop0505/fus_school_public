<?php

use yii\db\Migration;

class m170410_112546_AddApprovedById extends Migration
{
    public function up()
    {
		$this->addColumn('Post', 'approvedById', 'int(10) UNSIGNED default NULL after updatedById');
		$this->createIndex('approvedById', 'Post', 'approvedById');
		$this->addForeignKey('approvedByIdPost', 'Post', 'approvedById', 'User', 'id', 'RESTRICT', 'RESTRICT');

		$this->execute("Update Post p join User u1 on p.createdById = u1.id join User u2 on p.updatedById = u2.id set p.approvedById = p.updatedById where p.isApproved = 1 and u1.institutionId = u2.institutionId");
    }

    public function down()
    {
		$this->dropForeignKey('approvedByIdPost', 'Post');
		$this->dropIndex('approvedById', 'Post');
    	$this->dropColumn('Post', 'approvedById');
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

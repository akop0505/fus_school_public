<?php

use yii\db\Migration;

class m160603_154231_fixes extends Migration
{
	public function up()
	{
		$this->alterColumn('City', 'lat', 'decimal(9,2) NULL');
		$this->alterColumn('City', 'lon', 'decimal(9,2) NULL');
		$this->alterColumn('City', 'timeZoneId', 'int unsigned NOT NULL');
		$this->dropForeignKey('cityIdUser', 'User');
		$this->dropColumn('User', 'cityId');
		$this->dropColumn('User', 'address');
		$this->addColumn('City', 'isActive', 'tinyint(1) not null default 1');
		$this->addColumn('Institution', 'isActive', 'tinyint(1) not null default 1');
		$this->createIndex('name', 'City', ['name', 'zip', 'stateId'], true);
		$this->addColumn('Institution', 'createdAt', 'datetime not null');
		$this->addColumn('Institution', 'createdById', 'int unsigned not null');
		$this->addColumn('Institution', 'updatedAt', 'datetime not null');
		$this->addColumn('Institution', 'updatedById', 'int unsigned not null');
		$this->createIndex('createdById', 'Institution', 'createdById');
		$this->createIndex('updatedById', 'Institution', 'createdById');
		$this->addForeignKey('createdByIdInstitution', 'Institution', 'createdById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('updatedByIdInstitution', 'Institution', 'updatedById', 'User', 'id', 'RESTRICT', 'RESTRICT');
		$this->alterColumn('User', 'timeZoneId', 'int unsigned NOT NULL');
		$this->addColumn('User', 'institutionId', 'int unsigned null');
		$this->createIndex('institutionId', 'User', 'institutionId');
		$this->addForeignKey('institutionIdUser', 'User', 'institutionId', 'Institution', 'id', 'RESTRICT', 'RESTRICT');
	}

	public function down()
	{
		$this->dropForeignKey('institutionIdUser', 'User');
		$this->dropColumn('User', 'institutionId');
		$this->alterColumn('User', 'timeZoneId', 'int unsigned NULL');
		$this->dropForeignKey('updatedByIdInstitution', 'Institution');
		$this->dropColumn('Institution', 'updatedById');
		$this->dropColumn('Institution', 'updatedAt');
		$this->dropForeignKey('createdByIdInstitution', 'Institution');
		$this->dropColumn('Institution', 'createdById');
		$this->dropColumn('Institution', 'createdAt');
		$this->dropIndex('name', 'City');
		$this->dropColumn('Institution', 'isActive');
		$this->dropColumn('City', 'isActive');
		$this->addColumn('User', 'address', 'varchar(255)');
		$this->addColumn('User', 'cityId', 'int(10) unsigned default null after dateOfBirth');
		$this->createIndex('cityId', 'User', 'cityId');
		$this->addForeignKey('cityIdUser', 'User', 'cityId', 'City', 'id', 'RESTRICT', 'RESTRICT');
		$this->alterColumn('City', 'timeZoneId', 'int unsigned NULL');
		$this->alterColumn('City', 'lon', 'decimal(9,2) NOT NULL');
		$this->alterColumn('City', 'lat', 'decimal(9,2) NOT NULL');
	}
}

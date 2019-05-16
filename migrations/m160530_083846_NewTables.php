<?php

use yii\db\Migration;

class m160530_083846_NewTables extends Migration
{
    public function up()
    {
        $this->execute('update log set log_time = null');
        $this->alterColumn('log', 'log_time', 'datetime null default null');

        $this->createTable('TimeZone', array(
            'id' => 'int(10) unsigned NOT NULL auto_increment',
            'name' => 'varchar(255) NOT NULL',
            'PRIMARY KEY (id)'
        ));

        $this->insert('TimeZone', array(
            'id' => 1,
            'name' => 'America/Adak'
        ));

        $this->insert('TimeZone', array(
            'id' => 2,
            'name' => 'America/Anchorage'
        ));

        $this->insert('TimeZone', array(
            'id' => 3,
            'name' => 'America/Boise'
        ));

        $this->insert('TimeZone', array(
            'id' => 4,
            'name' => 'America/Chicago'
        ));

        $this->insert('TimeZone', array(
            'id' => 5,
            'name' => 'America/Denver'
        ));

        $this->insert('TimeZone', array(
            'id' => 6,
            'name' => 'America/Detroit'
        ));

        $this->insert('TimeZone', array(
            'id' => 7,
            'name' => 'America/Indiana/Indianapolis'
        ));

        $this->insert('TimeZone', array(
            'id' => 8,
            'name' => 'America/Indiana/Knox'
        ));

        $this->insert('TimeZone', array(
            'id' => 9,
            'name' => 'America/Indiana/Marengo'
        ));

        $this->insert('TimeZone', array(
            'id' => 10,
            'name' => 'America/Indiana/Petersburg'
        ));

        $this->insert('TimeZone', array(
            'id' => 11,
            'name' => 'America/Indiana/Tell_City'
        ));

        $this->insert('TimeZone', array(
            'id' => 12,
            'name' => 'America/Indiana/Vevay'
        ));

        $this->insert('TimeZone', array(
            'id' => 13,
            'name' => 'America/Indiana/Vincennes'
        ));

        $this->insert('TimeZone', array(
            'id' => 14,
            'name' => 'America/Indiana/Winamac'
        ));

        $this->insert('TimeZone', array(
            'id' => 15,
            'name' => 'America/Juneau'
        ));

        $this->insert('TimeZone', array(
            'id' => 16,
            'name' => 'America/Kentucky/Louisville'
        ));

        $this->insert('TimeZone', array(
            'id' => 17,
            'name' => 'America/Kentucky/Monticello'
        ));

        $this->insert('TimeZone', array(
            'id' => 18,
            'name' => 'America/Los_Angeles'
        ));

        $this->insert('TimeZone', array(
            'id' => 19,
            'name' => 'America/Menominee'
        ));

        $this->insert('TimeZone', array(
            'id' => 20,
            'name' => 'America/Metlakatla'
        ));

        $this->insert('TimeZone', array(
            'id' => 21,
            'name' => 'America/New_York'
        ));

        $this->insert('TimeZone', array(
            'id' => 22,
            'name' => 'America/Nome'
        ));

        $this->insert('TimeZone', array(
            'id' => 23,
            'name' => 'America/North_Dakota/Beulah'
        ));

        $this->insert('TimeZone', array(
            'id' => 24,
            'name' => 'America/North_Dakota/Center'
        ));

        $this->insert('TimeZone', array(
            'id' => 25,
            'name' => 'America/North_Dakota/New_Salem'
        ));

        $this->insert('TimeZone', array(
            'id' => 26,
            'name' => 'America/Phoenix'
        ));

        $this->insert('TimeZone', array(
            'id' => 27,
            'name' => 'America/Sitka'
        ));

        $this->insert('TimeZone', array(
            'id' => 28,
            'name' => 'America/Yakutat'
        ));

        $this->insert('TimeZone', array(
            'id' => 29,
            'name' => 'Pacific/Honolulu'
        ));

        $this->insert('TimeZone', array(
            'id' => 30,
            'name' => 'America/Puerto_Rico'
        ));

        $this->insert('TimeZone', array(
            'id' => 31,
            'name' => 'America/St_Thomas'
        ));

        $this->insert('TimeZone', array(
            'id' => 32,
            'name' => 'Pacific/Guam'
        ));

        $this->insert('TimeZone', array(
            'id' => 33,
            'name' => 'Pacific/Palau'
        ));

        $this->addColumn('City', 'timeZoneId', 'int(10) unsigned default null after timeZone');
        $this->createIndex('timeZoneId', 'City', 'timeZoneId');
        $this->addForeignKey('timeZoneIdCity', 'City', 'timeZoneId', 'TimeZone', 'id', 'RESTRICT', 'RESTRICT');

        $this->update('City', array('timeZoneId' => 30), 'timeZone = "America/Puerto_Rico"');
        $this->update('City', array('timeZoneId' => 21), 'timeZone = "America/New_York"');
        $this->update('City', array('timeZoneId' => 31), 'timeZone = "America/St_Thomas"');
        $this->update('City', array('timeZoneId' => 4), 'timeZone = "America/Chicago"');
        $this->update('City', array('timeZoneId' => 7), 'timeZone = "America/Indiana/Indianapolis"');
        $this->update('City', array('timeZoneId' => 16), 'timeZone = "America/Kentucky/Louisville"');
        $this->update('City', array('timeZoneId' => 9), 'timeZone = "America/Indiana/Marengo"');
        $this->update('City', array('timeZoneId' => 17), 'timeZone = "America/Kentucky/Monticello"');
        $this->update('City', array('timeZoneId' => 12), 'timeZone = "America/Indiana/Vevay"');
        $this->update('City', array('timeZoneId' => 5), 'timeZone = "America/Denver"');
        $this->update('City', array('timeZoneId' => 25), 'timeZone = "America/North_Dakota/New_Salem"');
        $this->update('City', array('timeZoneId' => 23), 'timeZone = "America/North_Dakota/Beulah"');
        $this->update('City', array('timeZoneId' => 24), 'timeZone = "America/North_Dakota/Center"');
        $this->update('City', array('timeZoneId' => 3), 'timeZone = "America/Boise"');
        $this->update('City', array('timeZoneId' => 26), 'timeZone = "America/Phoenix"');
        $this->update('City', array('timeZoneId' => 18), 'timeZone = "America/Los_Angeles"');
        $this->update('City', array('timeZoneId' => 2), 'timeZone = "America/Anchorage"');
        $this->update('City', array('timeZoneId' => 15), 'timeZone = "America/Juneau"');
        $this->update('City', array('timeZoneId' => 27), 'timeZone = "America/Sitka"');
        $this->update('City', array('timeZoneId' => 32), 'timeZone = "Pacific/Guam"');
        $this->update('City', array('timeZoneId' => 29), 'timeZone = "Pacific/Honolulu"');
        $this->update('City', array('timeZoneId' => 33), 'timeZone = "Pacific/Palau"');
        $this->update('City', array('timeZoneId' => 13), 'timeZone = "America/Indiana/Vincennes"');
        $this->update('City', array('timeZoneId' => 11), 'timeZone = "America/Indiana/Tell_City"');
        $this->update('City', array('timeZoneId' => 14), 'timeZone = "America/Indiana/Winamac"');
        $this->update('City', array('timeZoneId' => 8), 'timeZone = "America/Indiana/Knox"');
        $this->update('City', array('timeZoneId' => 10), 'timeZone = "America/Indiana/Petersburg"');
        $this->update('City', array('timeZoneId' => 6), 'timeZone = "America/Detroit"');
        $this->update('City', array('timeZoneId' => 19), 'timeZone = "America/Menominee"');

        $this->dropColumn('City', 'timeZone');
        $this->alterColumn('City', 'id', 'int(10) unsigned NOT NULL auto_increment');

        $this->createTable('Institution', array(
            'id' => 'int(10) unsigned NOT NULL auto_increment',
            'name' => 'varchar(255) NOT NULL',
            'cityId' => 'int(10) unsigned NOT NULL',
            'address' => 'varchar(255) NOT NULL',
            'PRIMARY KEY (id)',
            'KEY (cityId)'
        ));

        $this->addForeignKey('cityIdInstitution', 'Institution', 'cityId', 'City', 'id', 'RESTRICT', 'RESTRICT');

        $this->addColumn('User', 'timeZoneId', 'int(10) unsigned default null after timeZone');
        $this->createIndex('timeZoneId', 'User', 'timeZoneId');
        $this->addForeignKey('timeZoneIdUser', 'User', 'timeZoneId', 'TimeZone', 'id', 'RESTRICT', 'RESTRICT');
        $this->dropColumn('User', 'timeZone');

        $this->addColumn('User', 'cityId', 'int(10) unsigned default null after city');
        $this->createIndex('cityId', 'User', 'cityId');
        $this->addForeignKey('cityIdUser', 'User', 'cityId', 'City', 'id', 'RESTRICT', 'RESTRICT');
        $this->dropColumn('User', 'city');

        $this->dropColumn('User', 'countryId');
    }

    public function down()
    {
        $this->alterColumn('log', 'log_time', 'double null default null');
        $this->dropForeignKey('cityIdInstitution', 'Institution');
        $this->dropTable('Institution');

        $this->dropForeignKey('timeZoneIdUser', 'User');
        $this->dropIndex('timeZoneId', 'User');
        $this->dropColumn('User', 'timeZoneId');

        $this->dropForeignKey('cityIdUser', 'User');
        $this->dropIndex('cityId', 'User');
        $this->dropColumn('User', 'cityId');

        $this->dropForeignKey('timeZoneIdCity', 'City');
        $this->dropIndex('timeZoneId', 'City');
        $this->dropColumn('City', 'timeZoneId');

        $this->dropTable('TimeZone');

        $this->addColumn('User', 'city', 'varchar(255) default null after dateOfBirth');
        $this->addColumn('User', 'countryId', 'int(10) unsigned default null after mobilePhone');
        $this->createIndex('countryId', 'User', 'countryId');
        $this->addColumn('User', 'timeZone', 'varchar(64) default null after countryId');
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

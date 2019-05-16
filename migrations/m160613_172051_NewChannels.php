<?php

use yii\db\Migration;
use yii\db\Expression;

class m160613_172051_NewChannels extends Migration
{
    public function up()
    {
        $this->insert('Channel', array(
            'id' => 1,
            'name' => 'Home Latest',
            'isSystem' => 1,
            'createdById' => 1,
            'updatedById' => 1,
            'createdAt' => new Expression('NOW()'),
            'updatedAt' => new Expression('NOW()'),
        ));

        $this->insert('Channel', array(
            'id' => 2,
            'name' => 'Home Must See',
            'isSystem' => 1,
            'createdById' => 1,
            'updatedById' => 1,
            'createdAt' => new Expression('NOW()'),
            'updatedAt' => new Expression('NOW()'),
        ));

        $this->insert('Channel', array(
            'id' => 3,
            'name' => 'Home Slider',
            'isSystem' => 1,
            'createdById' => 1,
            'updatedById' => 1,
            'createdAt' => new Expression('NOW()'),
            'updatedAt' => new Expression('NOW()'),
        ));

        $this->insert('Channel', array(
            'id' => 4,
            'name' => 'Home Below Slider',
            'isSystem' => 1,
            'createdById' => 1,
            'updatedById' => 1,
            'createdAt' => new Expression('NOW()'),
            'updatedAt' => new Expression('NOW()'),
        ));

        $this->addColumn('Post', 'video', 'varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL AFTER hasThumbPhoto');
    }

    public function down()
    {
        $this->delete('Channel', 'id < 5');
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

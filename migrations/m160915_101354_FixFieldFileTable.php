<?php

use yii\db\Migration;
use yii\db\Expression;

class m160915_101354_FixFieldFileTable extends Migration
{
    public function up()
    {
        $this->alterColumn('FileUpload', 'fileName', 'varchar(255) NOT NULL');

		$this->insert('Content', array(
            'urlSlug' => 'resources-partial',
            'title' => 'Overview Video',
            'bodyText' => '',
			'extraHtml' => '',
			'createdById' => 1,
			'updatedById' => 1,
			'createdAt' => new Expression('NOW()'),
			'updatedAt' => new Expression('NOW()'),
        ));

		$this->insert('Content', array(
			'urlSlug' => 'resources-overview',
			'title' => 'Overview video',
			'bodyText' => '',
			'extraHtml' => '',
			'createdById' => 1,
			'updatedById' => 1,
			'createdAt' => new Expression('NOW()'),
			'updatedAt' => new Expression('NOW()'),
		));

		$this->insert('Content', array(
			'urlSlug' => 'resources-product',
			'title' => 'Product Overview',
			'bodyText' => '',
			'extraHtml' => '',
			'createdById' => 1,
			'updatedById' => 1,
			'createdAt' => new Expression('NOW()'),
			'updatedAt' => new Expression('NOW()'),
		));

		$this->insert('Content', array(
			'urlSlug' => 'resources-presentation',
			'title' => 'Presentation',
			'bodyText' => '',
			'extraHtml' => '',
			'createdById' => 1,
			'updatedById' => 1,
			'createdAt' => new Expression('NOW()'),
			'updatedAt' => new Expression('NOW()'),
		));

		$this->insert('Content', array(
			'urlSlug' => 'resources-faq',
			'title' => 'FAQs',
			'bodyText' => '',
			'extraHtml' => '',
			'createdById' => 1,
			'updatedById' => 1,
			'createdAt' => new Expression('NOW()'),
			'updatedAt' => new Expression('NOW()'),
		));


		$this->insert('Content', array(
			'urlSlug' => 'resources-swag',
			'title' => 'SWAG',
			'bodyText' => '',
			'extraHtml' => '',
			'createdById' => 1,
			'updatedById' => 1,
			'createdAt' => new Expression('NOW()'),
			'updatedAt' => new Expression('NOW()'),
		));

		$this->insert('Content', array(
			'urlSlug' => 'resources-photos',
			'title' => 'Photos',
			'bodyText' => '',
			'extraHtml' => '',
			'createdById' => 1,
			'updatedById' => 1,
			'createdAt' => new Expression('NOW()'),
			'updatedAt' => new Expression('NOW()'),
		));

		$this->insert('Content', array(
			'urlSlug' => 'resources-agreement',
			'title' => 'Agreement',
			'bodyText' => '',
			'extraHtml' => '',
			'createdById' => 1,
			'updatedById' => 1,
			'createdAt' => new Expression('NOW()'),
			'updatedAt' => new Expression('NOW()'),
		));

		$this->insert('Content', array(
			'urlSlug' => 'resources-testimonials',
			'title' => 'Testimonials',
			'bodyText' => '',
			'extraHtml' => '',
			'createdById' => 1,
			'updatedById' => 1,
			'createdAt' => new Expression('NOW()'),
			'updatedAt' => new Expression('NOW()'),
		));
    }

    public function down()
    {
        $this->alterColumn('FileUpload', 'fileName', 'varchar(64) NOT NULL');
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

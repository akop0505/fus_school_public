<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "StudentsFeatured".
 */
class StudentsFeatured extends base\StudentsFeatured
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}
}

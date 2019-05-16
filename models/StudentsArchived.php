<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "StudentsArchived".
 */
class StudentsArchived extends base\StudentsArchived
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}
}

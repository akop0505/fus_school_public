<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "TimeZone".
 */
class TimeZone extends base\TimeZone
{
	public function representingColumn()
	{
		return 'name';
	}
}

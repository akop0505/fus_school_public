<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "City".
 */
class City extends base\City
{
	public function representingColumn()
	{
		return 'name';
	}
}

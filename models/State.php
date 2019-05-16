<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "State".
 */
class State extends base\State
{
	public function representingColumn()
	{
		return 'name';
	}
}

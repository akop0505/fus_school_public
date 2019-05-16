<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "DiscoverChannel".
 */
class DiscoverChannel extends base\DiscoverChannel
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}
}

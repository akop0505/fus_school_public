<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "PostChannel".
 */
class PostChannel extends base\PostChannel
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}
}

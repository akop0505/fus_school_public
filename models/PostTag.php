<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "PostTag".
 */
class PostTag extends base\PostTag
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}
}

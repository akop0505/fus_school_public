<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "PostFeatured".
 */
class PostFeatured extends base\PostFeatured
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}
}

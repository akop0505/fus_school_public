<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "TagFeatured".
 */
class TagFeatured extends base\TagFeatured
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}
}

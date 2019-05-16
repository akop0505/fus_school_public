<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "PostMedia".
 */
class PostMedia extends base\PostMedia
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return 'filename';
	}
}

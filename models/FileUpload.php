<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "FileUpload".
 */
class FileUpload extends base\FileUpload
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return 'fileName';
	}
}

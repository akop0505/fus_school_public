<?php

namespace app\models;

use yii\helpers\Url;

/**
 * This is the model class for table "Tag".
 */
class Tag extends base\Tag
{
	public function representingColumn()
	{
		return 'name';
	}

	/**
	 * Return tag url
	 * @param bool $scheme
	 * @return string
	 */
	public function getUrl($scheme = false)
	{
		return Url::toRoute(['/site/tag', 'item' => $this], $scheme);
	}
}

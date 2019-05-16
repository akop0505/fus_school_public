<?php

namespace app\models;

use yii\helpers\Url;

/**
 * This is the model class for table "Content".
 */
class Content extends base\Content
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return 'urlSlug';
	}

	/**
	 * Return tag url
	 * @param bool $scheme
	 * @return string
	 */
	public function getUrl($scheme = false)
	{
		return Url::toRoute(['site/content', 'contentType' => $this->urlSlug], $scheme);
	}
}

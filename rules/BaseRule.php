<?php

namespace app\rules;

use yii\web\UrlRuleInterface;

abstract class BaseRule implements UrlRuleInterface
{
	/**
	 * Replaces dashes with blank spaces
	 * @param $name
	 * @return mixed
	 */
	static public function nameFromLink($name)
	{
		return $str = preg_replace("/[-]{2,}/", "-", preg_replace("/[^a-zA-Z0-9-]/i", "", str_replace(' ', '-', $name)));
	}
}
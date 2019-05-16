<?php

namespace app\i18n;

use Yii;
use yii\i18n\Formatter as BaseFormatter;

class Formatter extends BaseFormatter
{
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		if(!Yii::$app->user->isGuest) $this->timeZone = (string)Yii::$app->user->identity->timeZone;
		parent::init();
	}

	/**
	 * Unformat number formatted by this class
	 * @param string $value
	 * @return mixed
	 */
	public function unformatDecimal($value)
	{
		if($value === null || $value === '' || is_float($value)) return $value;

		return str_replace([$this->thousandSeparator, $this->decimalSeparator], ['', '.'], $value);
	}
}

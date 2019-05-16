<?php

namespace app\validators;

use Yii;
use yii\helpers\Json;
use yii\validators\NumberValidator;
use yii\validators\ValidationAsset;
use yii\web\JsExpression;

class LocalNumberValidator extends NumberValidator
{
	/**
	 * @inheritdoc
	 */
	public function clientValidateAttribute($model, $attribute, $view)
	{
		$label = $model->getAttributeLabel($attribute);

		$options = [
			// changed number pattern to local format !!!!!
			'pattern' => new JsExpression($this->integerOnly ? $this->integerPattern : str_replace('.', Yii::$app->components['formatter']['decimalSeparator'], $this->numberPattern)),
			'message' => Yii::$app->getI18n()->format($this->message, [
				'attribute' => $label,
			], Yii::$app->language),
		];

		if ($this->min !== null) {
			// ensure numeric value to make javascript comparison equal to PHP comparison
			// https://github.com/yiisoft/yii2/issues/3118
			$options['min'] = is_string($this->min) ? (float)$this->min : $this->min;
			$options['tooSmall'] = Yii::$app->getI18n()->format($this->tooSmall, [
				'attribute' => $label,
				'min' => $this->min,
			], Yii::$app->language);
		}
		if ($this->max !== null) {
			// ensure numeric value to make javascript comparison equal to PHP comparison
			// https://github.com/yiisoft/yii2/issues/3118
			$options['max'] = is_string($this->max) ? (float)$this->max : $this->max;
			$options['tooBig'] = Yii::$app->getI18n()->format($this->tooBig, [
				'attribute' => $label,
				'max' => $this->max,
			], Yii::$app->language);
		}
		if ($this->skipOnEmpty) {
			$options['skipOnEmpty'] = 1;
		}

		ValidationAsset::register($view);

		return 'yii.validation.number(value, messages, ' . Json::htmlEncode($options) . ');';
	}
}
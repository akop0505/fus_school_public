<?php
namespace app\models\common;

use Yii;
use yii\base\Model;

/**
 * Class BaseModel
 * @package app\models\common

 */
abstract class BaseModel extends Model
{
	/**
	 * @var array
	 */
	protected $_decimalFields = [];

	/**
	 * Sets the attribute values in a massive way.
	 * @param array $values attribute values (name => value) to be assigned to the model.
	 * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
	 * A safe attribute is one that is associated with a validation rule in the current [[scenario]].
	 * @see safeAttributes()
	 * @see attributes()
	 */
	public function setAttributes($values, $safeOnly = true)
	{
		if(is_array($values))
		{
			$attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
			foreach($values as $name => $value)
			{
				if(isset($attributes[$name]))
				{
					if(isset($this->_decimalFields[$name]))
					{
						/**
						 * @var $formatter \app\i18n\Formatter
						 */
						$formatter = Yii::$app->formatter;
						$value = $formatter->unformatDecimal($value);
					}
					$this->$name = $value;
				}
				elseif($safeOnly) $this->onUnsafeAttribute($name, $value);
			}
		}
	}

	/**
	 * Return standard yes (0) / no (1) options for dropdown
	 * @return array
	 */
	public static function dropdownYesNo()
	{
		return [0 => Yii::t('app', 'No'), 1 => Yii::t('app', 'Yes')];
	}
}
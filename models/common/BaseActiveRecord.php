<?php
namespace app\models\common;

use Yii;
use app\behaviors\AuthorBeforeValidateBehavior;
use app\behaviors\TimestampBeforeValidateBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\base\InvalidConfigException;

/**
 * Class BaseActiveRecord
 * @package app\models\common
 *
 * @property integer $id
 */
abstract class BaseActiveRecord extends ActiveRecord
{
	/**
	 * @var string The separator (delimiter) used to separate the {@link representingColumn}
	 * values when there are multiple representing columns while building the
	 * string representation of the record in {@link __toString}.
	 */
	public $repColumnsSeparator = '-';

	/**
	 * @return array
	 */
	public function behaviors()
	{
		$b = parent::behaviors();
		if($this->hasAttribute('createdAt') || $this->hasAttribute('updatedAt'))
		{
			$b[] = [
				'class' => TimestampBeforeValidateBehavior::className(),
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'updatedAt',
				'value' => new Expression('UTC_TIMESTAMP()'),
			];
		}
		if($this->hasAttribute('createdById') || $this->hasAttribute('updatedById'))
		{
			$b[] = [
				'class' => AuthorBeforeValidateBehavior::className(),
			];
		}
		return $b;
	}

	/**
	 * Returns relation label
	 * @param string $relationName
	 * @param null|int $n
	 * @return string
	 */
	public function getRelationLabel($relationName, $n = null)
	{
		$relations = $this->relations();
		if(!isset($relations[$relationName])) return parent::getAttributeLabel($relationName);
		$relation = $relations[$relationName];
		// Automatically apply the correct number if requested.
		if($n === null)
		{
			switch($relation[0])
			{
				case 'HAS_MANY':
				case 'MANY_MANY':
					$n = 2;
					break;
				case 'BELONGS_TO':
				case 'HAS_ONE':
				default :
					$n = 1;
					break;
			}
		}
		$ar = $relation[1];
		// Get and return the label from the related AR.
		/**
		 * @var $ar BaseActiveRecord
		 */
		return $ar::label($n);
	}


	/**
	 * Returns the text label for the specified attribute.
	 * @param string $attribute
	 * @return string
	 */
	public function getAttributeLabel($attribute)
	{
		$attributeLabels = $this->attributeLabels();
		if(!isset($attributeLabels[$attribute]) || $attributeLabels[$attribute] === null) return $this->getRelationLabel($attribute);
		elseif($attributeLabels[$attribute] === false)
		{
			$tmp = substr($attribute, 0, -2);
			if(isset($attributeLabels[$tmp])) return $attributeLabels[$tmp];
			return $this->getRelationLabel($tmp);
		}
		return parent::getAttributeLabel($attribute);
	}

	/**
	 * Returns model label
	 * @param int $n
	 * @return string
	 * @throws InvalidConfigException
	 */
	public static function label($n = 1)
	{
		throw new InvalidConfigException(Yii::t('app', 'This method should be overriden by the Active Record class.', ['n' => $n]));
	}

	/**
	 * Returns relations data array
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function relations()
	{
		throw new InvalidConfigException(Yii::t('app', 'This method should be overriden by the Active Record class.'));
	}

	/**
	 * The specified column(s) is(are) the responsible for the
	 * string representation of the model instance.
	 * The column is used in the {@link __toString} default implementation.
	 * Every model must specify the attributes used to build their
	 * string representation by overriding this method.
	 * This method must be overridden in each model class
	 * that extends this class.
	 * @return string|array The name of the representing column for the table (string) or
	 * the names of the representing columns (array).
	 * @see __toString
	 */
	public function representingColumn()
	{
		return null;
	}

	/**
	 * Returns a string representation of the model instance, based on
	 * {@link representingColumn}.
	 * When you override this method, all model attributes used to build
	 * the string representation of the model must be specified in
	 * {@link representingColumn}.
	 * @return string The string representation for the model instance.
	 * @throws InvalidConfigException If {@link representingColumn} is not defined.
	 * @uses representingColumn
	 * @uses repColumnsSeparator
	 */
	public function __toString()
	{
		$representingColumn = $this->representingColumn();

		if(empty($representingColumn))
		{
			throw new InvalidConfigException(Yii::t('app', 'The representing column for the active record "{model}" is not set.', ['{model}' => get_class($this)]));
		}

		if(is_array($representingColumn))
		{
			$repValues = array();
			foreach($representingColumn as $repColumnItem) $repValues[] = $this->getRepresentingColumnValueByLang($repColumnItem);
			return implode($this->repColumnsSeparator, $repValues);
		}
		else return $this->getRepresentingColumnValueByLang($representingColumn);
	}

	/**
	 * Get single representing column value by language is possible
	 * @param string $name
	 * @return string
	 */
	protected function getRepresentingColumnValueByLang($name)
	{
		if($this->$name === null) return '';
		if(is_object($this->$name)) return (string)$this->$name;
		$lang = ucfirst(substr(Yii::$app->language, 0, 2));
		if($this->hasAttribute($name . $lang) && $this->{$name . $lang}) return (string)$this->{$name . $lang};
		return (string)$this->$name;
	}

	/**
	 * Return array for dropdown list
	 * @param mixed $condition
	 * @return array
	 */
	public static function dropDownFind($condition = false)
	{
		$ret = [];
		if($condition === false) $records = self::find()->all();
		else
		{
			if(!is_array($condition)) $condition = [self::primaryKey()[0] => $condition];
            $recordsQuery = self::find()->where($condition);
            if(self::className() === 'app\\models\\User')
                $recordsQuery->orderBy("username");
            else
                $recordsQuery->orderBy("name");
            $records = $recordsQuery->all();
		}
		if(!$records) return $ret;
		/**
		 * @var BaseActiveRecord $record
		 */
		foreach($records as $record)
		{
			$pkVal = $record->getPrimaryKey(true);
			$ret[implode('-', $pkVal)] = (string)$record;
		}
		return $ret;
	}

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
					$column = $this->getTableSchema()->getColumn($name);
					if($column)
					{
						if(stripos($column->dbType, 'decimal') !== false)
						{
							/**
							 * @var $formatter \app\i18n\Formatter
							 */
							$formatter = Yii::$app->formatter;
							$value = $formatter->unformatDecimal($value);
						}
					}
					$this->$name = $value;
				}
				elseif($safeOnly) $this->onUnsafeAttribute($name, $value);
			}
		}
	}

	/**
	 * Prepare attribute data for form display
	 * - changes numbers according to formatter settings
	 */
	public function prepareForForm()
	{
		$this->setScenario('formModel');
		$attributes = $this->attributes();
		foreach($attributes as $name)
		{
			$column = $this->getTableSchema()->getColumn($name);
			if($column)
			{
				if(stripos($column->dbType, 'decimal') !== false)
				{
					$value = $this->$name;
					$this->$name = ($value && is_numeric($value)) ? Yii::$app->formatter->asDecimal($value) : $value;
				}
			}
		}
	}

	/**
	 * Return standard yes (1) / no (0) options for dropdown
	 * @return array
	 */
	public static function dropdownYesNo()
	{
		return [1 => Yii::t('app', 'Yes'), 0 => Yii::t('app', 'No')];
	}

	/**
	 * Return array of attributes that should be marked as unsafe
	 * @return array
	 */
	protected function getUnsafeAttributes()
	{
		return [];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios['formModel'] = $scenarios['default'];
		return $scenarios;
	}

	/**
	 * Implementations should return base path to store images in; by default constructed from model and attribute names
	 * @param string $attributeName
	 * @return string
	 */
	public function getPicBasePath($attributeName)
	{
		if($attributeName[0] == 'h' && $attributeName[1] == 'a' && $attributeName[2] == 's') $attributeName = substr($attributeName, 3);
		$baseClass = strtolower(substr(strrchr('\\'. get_called_class(), '\\'), 1));
		return Yii::getAlias('@webroot/images/upload/'. $baseClass .'/'. strtolower($attributeName) .'/'. $this->id .'/');
	}

	/**
	 * Implementations should return base url path to view images in; by default constructed from model and attribute names
	 * @param string $attributeName
	 * @return string
	 */
	public function getPicBaseUrl($attributeName)
	{
		if(!$this->$attributeName) return Yii::getAlias('@web/images/');
		if($attributeName[0] == 'h' && $attributeName[1] == 'a' && $attributeName[2] == 's') $attributeName = substr($attributeName, 3);
		$baseClass = strtolower(substr(strrchr('\\'. get_called_class(), '\\'), 1));
		return Yii::getAlias('@web/images/upload/'. $baseClass .'/'. strtolower($attributeName) .'/'. $this->id .'/');
	}

	/**
	 * Return placeholder name
	 * @param string $attributeName
	 * @return string
	 */
	protected function getPicPlaceholder($attributeName)
	{
		return 'post.jpg';
	}

	/**
	 * Return image filename
	 * @param string $attributeName
	 * @param bool $forDisplay
	 * @return string
	 */
	public function getPicName($attributeName, $forDisplay = false)
	{
		if($forDisplay && !$this->$attributeName) return $this->getPicPlaceholder($attributeName);
		$ext = '';
		//if($forDisplay && $this->hasAttribute('updatedAt')) $ext = '-'. str_replace([' ', ':', '-'], '', !is_object($this->updatedAt) ? $this->updatedAt : date('YmdHis'));
		$picName = '1' . $ext . '.jpg';
		if($forDisplay) {
		    // Add time to the end of image source, so cached versions will not be shown anymore.
		    $picName .= '?' . time();
		}
		return $picName;
	}
}
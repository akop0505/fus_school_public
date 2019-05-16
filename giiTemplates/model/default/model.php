<?php
/**
 * This is the template for generating the model class of a specified table.
 * DO NOT EDIT THIS FILE! It may be regenerated with Gii.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\model\Generator $generator
 * @var string $tableName full table name
 * @var string $className class name
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels list of attribute labels (name => label)
 * @var string[] $rules list of validation rules
 * @var array $relations list of relations (name => relation declaration)
 */

function getNS($name)
{
	$name = ltrim($name, '\\');
	$name = explode('\\', $name);
	array_pop($name);
	return implode('\\', $name);
}
function getClass($className, $name, &$ns, $namespacePrefix = '')
{
	$name = ltrim($name, '\\');
	$name = explode('\\', $name);
	$name = array_pop($name);
	if($className == $name)
	{
		$ns .= ' as '. $namespacePrefix . $name .'Use';
		return $name .'Use';
	}
	return $name;
}
function fixNS($name)
{
	return '\\'. ltrim($name, '\\');
}

$tableNameSingular = ucfirst($tableName);
$tableNamePlural = substr($tableNameSingular, -1) != 'y' ? ($tableNameSingular .'s') : (substr($tableNameSingular, 0, -1) .'ies');
$namespacePrefix = $tableNameSingular;
$useNS = $replaceNS = [];
$oneNS = fixNS($generator->baseClass);
if($oneNS)
{
	$firstName = $oneNS;
	$replaceNS[$firstName] = $namespacePrefix . getClass($className, $generator->baseClass, $oneNS, $namespacePrefix);
	$useNS[$oneNS] = 1;
}
$relations2 = array();
foreach($relations as $name => $relation)
{
	if(isset($relation[-1])) $relations2[$relation[-1]] = true;
	$matches = [];
	//asOne(\app\models\Category::className(),
	if(preg_match('/\(([\a-zA-Z]+)::className/i', $relation[0], $matches))
	{
		$oneNS = fixNS($matches[1]);
		if($oneNS)
		{
			$firstName = $oneNS;
			$replaceNS[$firstName] = $namespacePrefix . getClass($className, $oneNS, $oneNS, $namespacePrefix);
			$useNS[$oneNS] = 1;
		}
	}
}

echo "<?php\n";
?>

namespace <?= $generator->ns ?>\base;

use Yii;
<?php foreach($useNS as $key => $val)
{
	echo "use {$key}";
	if(stripos($key, ' as ') === false) echo " as {$replaceNS[$key]};\n";
	else echo ";\n";
} ?>

/**
 * This is the base-model class for table "<?= $tableName ?>".
 *
<?php foreach ($tableSchema->columns as $column): if($column->type == 'decimal') $column->phpType .= '|float';?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $namespacePrefix . ($relation[1] == $className ? ($relation[1] .'Use') : $relation[1]) . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= str_replace(array_keys($replaceNS), array_values($replaceNS), fixNS($generator->baseClass)) . "\n" ?>
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '<?= $tableName ?>';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return <?= 'Yii::t("app", "{n, plural, =1{' . $tableNameSingular . '} other{' . $tableNamePlural . '}}", ["n" => ' . ' $n])' ?>;
	}

	/**
	 * Return rule to mark attributes as unsafe or false if none
	 * @return bool|array
	 */
	protected function getUnsafeRule()
	{
<?php
	$unsafe = [];
	foreach($tableSchema->columns as $column)
	{
		if(strpos($column->name, 'created') !== false || strpos($column->name, 'updated') !== false) $unsafe[] = '!'. $column->name;
	}
	if(isset($unsafe[0])):
?>
		return [['<?php echo implode("', '", $unsafe); ?>'], 'safe'];
<?php else: ?>
		return false;
<?php endif; ?>
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = [<?= "\n\t\t\t" . implode(",\n\t\t\t", $rules) . "\n\t\t" ?>];
		if($tmp = $this->getUnsafeRule()) $r[] = $tmp;
		return $r;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		$ret = [
<?php foreach ($labels as $name => $label): ?>
			<?php echo "'$name' => ". $generator->generateString($label) .",\n";
			if($name == 'createdById' || $name == 'updatedById')
			{
				$name = str_replace('Id', '', $name);
				$label = str_ireplace(' ID', '', $generator->generateString($label));
				echo "\t\t\t'$name' => ". $label .",\n";
			}
			?>
<?php endforeach; ?>
		];
		if($this->getScenario() == 'formModel')
		{
<?php foreach ($relations as $name => $relation):
		if(!isset($relation[-1]) || !$relation[-1]) continue;
?>
			<?= "\$ret['" . $relation[-1] . "'] = false;\n" ?>
<?php endforeach; ?>
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
<?php foreach ($relations as $name => $relation): ?>
			<?= "'" . lcfirst($name) . "' => array('". (strpos($relation[0], 'hasMany') ? 'HAS_MANY' : 'BELONGS_TO') ."', " . ($namespacePrefix != $relation[1] ? $namespacePrefix : '') . $relation[1] . "::classname(), '". (isset($relation[-1]) ? $relation[-1] : '') ."'),\n" ?>
<?php endforeach; ?>
		];
	}

<?php foreach ($relations as $name => $relation): ?>
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function get<?= $name ?>()
	{
		<?= str_replace(array_keys($replaceNS), array_values($replaceNS), $relation[0]) . "\n" ?>
	}

<?php endforeach; ?>
	/**
	 * @inheritdoc
	 * @return \app\models\<?= $className ?>|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

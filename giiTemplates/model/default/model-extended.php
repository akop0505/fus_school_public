<?php
/**
 * This is the template for generating the model class of a specified table.
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

use yii\db\Schema;

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

/**
 * This is the model class for table "<?= $tableName ?>".
 */
class <?= $className ?> extends base\<?= $className . "\n" ?>
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
<?php
	$columnName = 'null';
	foreach($tableSchema->columns as $column)
	{
		if(!$column->allowNull && $column->type == Schema::TYPE_STRING)
		{
			$columnName = "'". $column->name ."'";
			break;
		}
	}
?>
		return <?= $columnName ?>;
	}
}

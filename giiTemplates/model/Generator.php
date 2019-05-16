<?php
namespace app\giiTemplates\model;

use schmunk42\giiant\generators\model\Generator as BaseGenerator;
use yii\base\NotSupportedException;

class Generator extends BaseGenerator
{
	/**
	 * COPY PASTE FROM YII ORIGINAL - private function
	 *
	 * Generates relations using a junction table by adding an extra viaTable().
	 * @param \yii\db\TableSchema the table being checked
	 * @param array $fks obtained from the checkPivotTable() method
	 * @param array $relations
	 * @return array modified $relations
	 */
	private function generateManyManyRelations($table, $fks, $relations)
	{
		$db = $this->getDbConnection();
		$table0 = $fks[$table->primaryKey[0]][0];
		$table1 = $fks[$table->primaryKey[1]][0];
		$className0 = $this->generateClassName($table0);
		$className1 = $this->generateClassName($table1);
		$table0Schema = $db->getTableSchema($table0);
		$table1Schema = $db->getTableSchema($table1);

		$link = $this->generateRelationLink([$fks[$table->primaryKey[1]][1] => $table->primaryKey[1]]);
		$viaLink = $this->generateRelationLink([$table->primaryKey[0] => $fks[$table->primaryKey[0]][1]]);
		$relationName = $this->generateRelationName($relations, $table0Schema, $table->primaryKey[1], true);
		$relations[$table0Schema->fullName][$relationName] = [
			"return \$this->hasMany($className1::className(), $link)->viaTable('"
			. $this->generateTableName($table->name) . "', $viaLink);",
			$className1,
			true,
		];

		$link = $this->generateRelationLink([$fks[$table->primaryKey[0]][1] => $table->primaryKey[0]]);
		$viaLink = $this->generateRelationLink([$table->primaryKey[1] => $fks[$table->primaryKey[1]][1]]);
		$relationName = $this->generateRelationName($relations, $table1Schema, $table->primaryKey[0], true);
		$relations[$table1Schema->fullName][$relationName] = [
			"return \$this->hasMany($className0::className(), $link)->viaTable('"
			. $this->generateTableName($table->name) . "', $viaLink);",
			$className0,
			true,
		];

		return $relations;
	}

	/**
	 * @return array the generated relation declarations
	 */
	protected function generateRelations()
	{
		if (!$this->generateRelations) {
			return [];
		}

		$db = $this->getDbConnection();

		$schema = $db->getSchema();
		if ($schema->hasMethod('getSchemaNames')) { // keep BC to Yii versions < 2.0.4
			try {
				$schemaNames = $schema->getSchemaNames();
			} catch (NotSupportedException $e) {
				// schema names are not supported by schema
			}
		}
		if (!isset($schemaNames)) {
			if (($pos = strpos($this->tableName, '.')) !== false) {
				$schemaNames = [substr($this->tableName, 0, $pos)];
			} else {
				$schemaNames = [''];
			}
		}

		$relations = [];
		foreach ($schemaNames as $schemaName) {
			foreach ($db->getSchema()->getTableSchemas($schemaName) as $table) {
				$className = $this->generateClassName($table->fullName);
				foreach ($table->foreignKeys as $refs) {
					$refTable = $refs[0];
					$refTableSchema = $db->getTableSchema($refTable);
					if($refTableSchema === null) continue;
					unset($refs[0]);
					$fks = array_keys($refs);
					$refClassName = $this->generateClassName($refTable);

					// Add relation for this table
					$link = $this->generateRelationLink(array_flip($refs));
					$relationName = $this->generateRelationName($relations, $table, $fks[0], false);
					$relations[$table->fullName][$relationName] = [
						"return \$this->hasOne($refClassName::className(), $link);",
						$refClassName,
						false,
						-1 => $fks[0]
					];

					// Add relation for the referenced table
					$uniqueKeys = [$table->primaryKey];
					try {
						$uniqueKeys = array_merge($uniqueKeys, $db->getSchema()->findUniqueIndexes($table));
					} catch (NotSupportedException $e) {
						// ignore
					}
					$hasMany = true;
					foreach ($uniqueKeys as $uniqueKey) {
						if (count(array_diff(array_merge($uniqueKey, $fks), array_intersect($uniqueKey, $fks))) === 0) {
							$hasMany = false;
							break;
						}
					}
					$link = $this->generateRelationLink($refs);
					$relationName = $this->generateRelationName($relations, $refTableSchema, $className, $hasMany);
					$relations[$refTableSchema->fullName][$relationName] = [
						"return \$this->" . ($hasMany ? 'hasMany' : 'hasOne') . "($className::className(), $link);",
						$className,
						$hasMany,
					];
				}

				if (($fks = $this->checkPivotTable($table)) === false) {
					continue;
				}

				if($table->fullName != 'SrGroupField') $relations = $this->generateManyManyRelations($table, $fks, $relations);
			}
		}

		// inject namespace
		$ns = "\\{$this->ns}\\";
		foreach($relations AS $model => $relInfo)
		{
			foreach($relInfo AS $relName => $relData)
			{
				$relations[$model][$relName][0] = preg_replace(
					'/(has[A-Za-z0-9]+\()([a-zA-Z0-9]+::)/',
					'$1__NS__$2',
					$relations[$model][$relName][0]
				);
				$relations[$model][$relName][0] = str_replace('__NS__', $ns, $relations[$model][$relName][0]);
			}
		}
		return $relations;
	}

	/**
	 * Checks if the given table is a junction table.
	 * For simplicity, this method only deals with the case where the pivot contains two PK columns,
	 * each referencing a column in a different table.
	 * @param \yii\db\TableSchema the table being checked
	 * @return array|boolean the relevant foreign key constraint information if the table is a junction table,
	 * or false if the table is not a junction table.
	 */
	protected function checkPivotTable($table)
	{
		$pk = $table->primaryKey;
		if (count($pk) !== 2) {
			return false;
		}
		$fks = [];
		foreach ($table->foreignKeys as $refs) {
			if (count($refs) === 2) {
				if (isset($refs[$pk[0]])) {
					$fks[$pk[0]] = [$refs[0], $refs[$pk[0]]];
				} elseif (isset($refs[$pk[1]])) {
					$fks[$pk[1]] = [$refs[0], $refs[$pk[1]]];
				}
			}
		}
		if (count($fks) === 2 && $fks[$pk[0]][0] !== $fks[$pk[1]][0]) {
			return $fks;
		} else {
			return false;
		}
	}

	/**
	 * @param \yii\db\TableSchema $table
	 * @return array
	 */
	public function generateRules($table)
	{
		$rules = parent::generateRules($table);
		foreach($rules as &$rule)
		{
			if(substr($rule, -11) === ", 'number']")
			{
				$rule = substr($rule, 0, -9) . "'app\\validators\\LocalNumberValidator']";
			}
			$rule = str_replace(['self::', '    '], ['static::', "\t"], $rule);
		}
		return $rules;
	}
}
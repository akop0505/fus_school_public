<?php
namespace app\giiTemplates\crud;

use yii\gii\generators\crud\Generator as BaseGenerator;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;

class Generator extends BaseGenerator
{
	public $useClasses = [];

	public function getName()
	{
		return 'Vortex CRUD Generator';
	}

	public function generateSearchConditions()
	{
		return str_replace("    ", "\t", parent::generateSearchConditions());
	}

	/**
	 * Generates code for active field
	 * @param string $attribute
	 * @return string
	 */
	public function generateActiveField($attribute)
	{
		$tableSchema = $this->getTableSchema();
		if($tableSchema === false || !isset($tableSchema->columns[$attribute]))
		{
			if(preg_match('/^(password|pass|passwd|passcode)$/i', $attribute))
			{
				return "\$form->field(\$model, '$attribute')->passwordInput()";
			}
			else
			{
				return "\$form->field(\$model, '$attribute')";
			}
		}
		$column = $tableSchema->columns[$attribute];
		if(isset($tableSchema->foreignKeys[0]))
		{
			foreach($tableSchema->foreignKeys as $fk)
			{
				if(!isset($fk[$attribute])) continue;
				$use = 'use app\models\\'. $fk[0] .';';
				$this->useClasses[$use] = $use;
				return "\$form->field(\$model, '$attribute')->dropDownList(". $fk[0] ."::dropDownFind(), ['prompt' => ''])";
			}
		}
		if($column->phpType === 'boolean')
		{
			return "\$form->field(\$model, '$attribute')->checkbox()";
		}
		elseif($column->type === 'text')
		{
			return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6])";
		}
		else
		{
			if(preg_match('/^(password|pass|passwd|passcode)$/i', $column->name))
			{
				$input = 'passwordInput';
			}
			else
			{
				$input = 'textInput';
			}
			if(is_array($column->enumValues) && count($column->enumValues) > 0)
			{
				$dropDownOptions = [];
				foreach($column->enumValues as $enumValue)
				{
					$dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
				}

				return "\$form->field(\$model, '$attribute')->dropDownList("
				. preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)) . ", ['prompt' => ''])";
			}
			elseif($column->phpType !== 'string' || $column->size === null)
			{
				return "\$form->field(\$model, '$attribute')->$input()";
			}
			else
			{
				return "\$form->field(\$model, '$attribute')->$input(['maxlength' => $column->size])";
			}
		}
	}
}
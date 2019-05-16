<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as StateBaseActiveRecord;
use \app\models\City as StateCity;

/**
 * This is the base-model class for table "State".
 *
 * @property string $code
 * @property string $name
 *
 * @property StateCity[] $cities
 */
class State extends StateBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'State';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{State} other{States}}", ["n" =>  $n]);
	}

	/**
	 * Return rule to mark attributes as unsafe or false if none
	 * @return bool|array
	 */
	protected function getUnsafeRule()
	{
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = [
			[['code', 'name'], 'required'],
			[['code'], 'string', 'max' => 2],
			[['name'], 'string', 'max' => 255]
		];
		if($tmp = $this->getUnsafeRule()) $r[] = $tmp;
		return $r;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		$ret = [
			'code' => Yii::t('models', 'Code'),
			'name' => Yii::t('models', 'Name'),
		];
		if($this->getScenario() == 'formModel')
		{
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'cities' => array('HAS_MANY', StateCity::className(), ''),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCities()
	{
		return $this->hasMany(StateCity::className(), ['stateId' => 'code']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\State|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

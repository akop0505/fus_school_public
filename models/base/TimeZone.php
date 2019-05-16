<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as TimeZoneBaseActiveRecord;
use \app\models\City as TimeZoneCity;
use \app\models\User as TimeZoneUser;

/**
 * This is the base-model class for table "TimeZone".
 *
 * @property integer $id
 * @property string $name
 *
 * @property TimeZoneCity[] $cities
 * @property TimeZoneUser[] $users
 */
class TimeZone extends TimeZoneBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'TimeZone';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{TimeZone} other{TimeZones}}", ["n" =>  $n]);
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
			[['name'], 'required'],
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
			'id' => Yii::t('models', 'ID'),
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
			'cities' => array('HAS_MANY', TimeZoneCity::className(), ''),
			'users' => array('HAS_MANY', TimeZoneUser::className(), ''),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCities()
	{
		return $this->hasMany(TimeZoneCity::className(), ['timeZoneId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers()
	{
		return $this->hasMany(TimeZoneUser::className(), ['timeZoneId' => 'id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\TimeZone|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

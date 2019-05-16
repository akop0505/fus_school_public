<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as CityBaseActiveRecord;
use \app\models\State as CityState;
use \app\models\TimeZone as CityTimeZone;
use \app\models\Institution as CityInstitution;

/**
 * This is the base-model class for table "City".
 *
 * @property integer $id
 * @property integer $zip
 * @property string $name
 * @property string $stateId
 * @property string|float $lat
 * @property string|float $lon
 * @property integer $timeZoneId
 * @property integer $isActive
 *
 * @property CityState $state
 * @property CityTimeZone $timeZone
 * @property CityInstitution[] $institutions
 */
class City extends CityBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'City';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{City} other{Cities}}", ["n" =>  $n]);
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
			[['zip', 'name', 'stateId', 'timeZoneId'], 'required'],
			[['zip', 'timeZoneId', 'isActive'], 'integer'],
			[['lat', 'lon'], 'app\validators\LocalNumberValidator'],
			[['name'], 'string', 'max' => 255],
			[['stateId'], 'string', 'max' => 2],
			[['name', 'zip', 'stateId'], 'unique', 'targetAttribute' => ['name', 'zip', 'stateId'], 'message' => 'The combination of Zip, Name and State ID has already been taken.'],
			[['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => CityState::className(), 'targetAttribute' => ['stateId' => 'code']],
			[['timeZoneId'], 'exist', 'skipOnError' => true, 'targetClass' => CityTimeZone::className(), 'targetAttribute' => ['timeZoneId' => 'id']]
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
			'zip' => Yii::t('models', 'Zip'),
			'name' => Yii::t('models', 'Name'),
			'stateId' => Yii::t('models', 'State ID'),
			'lat' => Yii::t('models', 'Lat'),
			'lon' => Yii::t('models', 'Lon'),
			'timeZoneId' => Yii::t('models', 'Time Zone ID'),
			'isActive' => Yii::t('models', 'Is Active'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['stateId'] = false;
			$ret['timeZoneId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'state' => array('BELONGS_TO', CityState::className(), 'stateId'),
			'timeZone' => array('BELONGS_TO', CityTimeZone::className(), 'timeZoneId'),
			'institutions' => array('HAS_MANY', CityInstitution::className(), ''),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getState()
	{
		return $this->hasOne(CityState::className(), ['code' => 'stateId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTimeZone()
	{
		return $this->hasOne(CityTimeZone::className(), ['id' => 'timeZoneId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitutions()
	{
		return $this->hasMany(CityInstitution::className(), ['cityId' => 'id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\City|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

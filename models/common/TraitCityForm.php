<?php
namespace app\models\common;

use app\behaviors\CityBeforeValidateBehavior;
use app\models\City;
use app\models\State;
use app\models\TimeZone;

trait TraitCityForm
{
	public $cityName;
	public $cityStateId;
	public $cityZip;
	public $cityTimeZoneId;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = parent::rules();
		$r[] = [['cityName', 'cityZip'], 'safe'];
		$r[] = ['cityTimeZoneId', 'integer'];
		$r[] = [['cityName', 'cityZip', 'cityStateId', 'cityTimeZoneId'], 'required'];
		$r[] = [['cityStateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['cityStateId' => 'code']];
		$r[] = [['cityTimeZoneId'], 'exist', 'skipOnError' => true, 'targetClass' => TimeZone::className(), 'targetAttribute' => ['cityTimeZoneId' => 'id']];
		return $r;
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$b = parent::behaviors();
		$b[] = [
			'class' => CityBeforeValidateBehavior::className(),
		];
		return $b;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		$ret = parent::attributeLabels();
		$ret['cityStateId'] = \Yii::t('app', 'State');
		$ret['cityZip'] = \Yii::t('app', 'ZIP');
		$ret['cityTimeZoneId'] = \Yii::t('app', 'TimeZone');
		return $ret;
	}

	public function loadCityData()
	{
		if($this->cityId && $this->city instanceof City)
		{
			$this->cityName = $this->city->name;
			$this->cityZip = $this->city->zip;
			$this->cityStateId = $this->city->stateId;
			$this->cityTimeZoneId = $this->city->timeZoneId;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function prepareForForm()
	{
		parent::prepareForForm();
		$this->loadCityData();
	}
}
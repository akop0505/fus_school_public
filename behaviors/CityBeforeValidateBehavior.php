<?php
namespace app\behaviors;

use app\models\City;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use Yii;

class CityBeforeValidateBehavior extends Behavior
{
	/**
	 * @inheritdoc
	 */
	public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'setCityId'
		];
	}

	/**
	 * Sets city ID
	 * @param $event
	 */
	public function setCityId($event)
	{
		$data = [
			'name' => $this->owner->cityName,
			'zip' => $this->owner->cityZip,
			'stateId' => $this->owner->cityStateId
		];
		$c = City::findOne($data);
		if($c)
		{
			$this->owner->setAttribute('cityId', $c->id);
			return;
		}
		$data['timeZoneId'] = $this->owner->cityTimeZoneId;
		$c = new City();
		$c->attributes = $data;
		if($c->save()) $this->owner->setAttribute('cityId', $c->id);
	}
}
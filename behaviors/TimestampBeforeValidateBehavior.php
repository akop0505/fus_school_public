<?php
namespace app\behaviors;

use \yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\db\ActiveRecord;

class TimestampBeforeValidateBehavior extends TimestampBehavior
{
	/**
	 * @inheritdoc
	 */
	public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'setTimestampValues'
		];
	}

	/**
	 * Sets last modified and created times
	 * @param $event
	 */
	public function setTimestampValues($event)
	{
		if($this->owner instanceof ActiveRecord)
		{
			$now = $this->getValue($event);
			if($this->owner->getIsNewRecord())
			{
				if($this->owner->hasAttribute($this->createdAtAttribute)) $this->owner->setAttribute($this->createdAtAttribute, $now);
			}
			if($this->owner->hasAttribute($this->updatedAtAttribute)) $this->owner->setAttribute($this->updatedAtAttribute, $now);
		}
	}
}
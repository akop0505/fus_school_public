<?php
namespace app\behaviors;

use yii\base\Behavior;
use yii\console\Application as ConsoleApp;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use Yii;

class AuthorBeforeValidateBehavior extends Behavior
{
	/**
	 * @var string the attribute that will receive created by value
	 */
	public $createdByAttribute = 'createdById';
	/**
	 * @var string the attribute that will receive updated by value.
	 */
	public $updatedByAttribute = 'updatedById';
	/**
	 * @var bool override created by with this ID
	 */
	private $overrideCreatedBy = false;
	/**
	 * @var bool override updated by with this ID
	 */
	private $overrideUpdatedBy = false;

	/**
	 * @param int $createdById
	 * @return $this
	 */
	public function setOverrideCreatedBy($createdById)
	{
		$this->overrideCreatedBy = $createdById;
		return $this;
	}

	/**
	 * @param int $updatedById
	 * @return $this
	 */
	public function setOverrideUpdatedBy($updatedById)
	{
		$this->overrideUpdatedBy = $updatedById;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'setAuthorValues'
		];
	}

	/**
	 * Sets author values
	 * @param $event
	 */
	public function setAuthorValues($event)
	{
		$app = Yii::$app;
		if($app instanceof ConsoleApp && !$app->has('user')) return;
		$user = $app->user;
		if($this->owner instanceof ActiveRecord && !$user->isGuest)
		{
			if($this->owner->getIsNewRecord())
			{
				if($this->owner->hasAttribute($this->createdByAttribute)) $this->owner->setAttribute($this->createdByAttribute, $this->overrideCreatedBy ?: $user->id);
			}
			if($this->owner->hasAttribute($this->updatedByAttribute)) $this->owner->setAttribute($this->updatedByAttribute, $this->overrideUpdatedBy ?: $user->id);
		}
	}
}
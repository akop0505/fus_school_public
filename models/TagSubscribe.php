<?php

namespace app\models;

use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "TagSubscribe".
 */
class TagSubscribe extends base\TagSubscribe
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function transactions()
	{
		return [
			'default' => self::OP_INSERT | self::OP_DELETE
		];
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if($insert)
		{
			$userActivity = new UserActivity();
			$userActivity->activityType = 'TagSubscribe';
			$userActivity->activityTypeFk = $this->tagId;
			if(!$userActivity->save()) throw new BadRequestHttpException(Yii::t('app', 'User Activity save failed!'));
		}
	}

	/**
	 * @inheritdoc
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		$userActivity = new UserActivity();
		$userActivity->activityType = 'TagSubscribe';
		$userActivity->activityTypeFk = $this->tagId;
		$userActivity->isRemove = 1;
		if(!$userActivity->save()) throw new BadRequestHttpException(Yii::t('app', 'User Activity save failed!'));
	}
}

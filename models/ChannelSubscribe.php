<?php

namespace app\models;

use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "ChannelSubscribe".
 */
class ChannelSubscribe extends base\ChannelSubscribe
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
			$userActivity->activityType = 'ChannelSubscribe';
			$userActivity->activityTypeFk = $this->channelId;
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
		$userActivity->activityType = 'ChannelSubscribe';
		$userActivity->activityTypeFk = $this->channelId;
		$userActivity->isRemove = 1;
		if(!$userActivity->save()) throw new BadRequestHttpException(Yii::t('app', 'User Activity save failed!'));
	}
}

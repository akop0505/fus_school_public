<?php

namespace app\models;

use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "PostRepost".
 */
class PostRepost extends base\PostRepost
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
			'default' => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE
		];
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if($this->isApproved == 1)
		{
			$findChannel = Channel::findOne(['institutionId' => $this->institutionId]);
			$postChannel = new PostChannel();
			$postChannel->postId = $this->postId;
			$postChannel->channelId = $findChannel->id;
			if($postChannel->save())
			{
				Channel::updateAllCounters(['numPosts' => 1], ['id' => $postChannel->channelId]);
			}
			else throw new BadRequestHttpException(Yii::t('app', 'Post Channel save failed!'));
		}
		else $this->removeFromChannel($this->institutionId, $this->postId);
	}

	protected function removeFromChannel($institutionId, $postId)
	{
		$findChannel = Channel::findOne(['institutionId' => $institutionId]);
		$postChannel = PostChannel::findOne(['channelId' => $findChannel->id, 'postId' => $postId]);
		if($postChannel)
		{
			if($postChannel->delete()) Channel::updateAllCounters(['numPosts' => -1], ['id' => $findChannel->id]);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		$this->removeFromChannel($this->institutionId, $this->postId);
}	}

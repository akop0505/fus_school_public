<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "Channel".
 */
class Channel extends base\Channel
{
	const CHANNEL_HOME_LATEST = 1;
	const CHANNEL_HOME_MUST_SEE = 2;
	const CHANNEL_HOME_SLIDER = 3;
	const CHANNEL_HOME_BELOW_SLIDER = 4;

	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return 'name';
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'hasPhoto' => Yii::t('app', 'Photo'),
				'hasPortraitPhoto' => Yii::t('app', 'Portrait Photo')
			)
		);
	}

	/**
	 * @inheritdoc
	 */
	protected function getUnsafeRule()
	{
		return [['!createdAt', '!createdById', '!updatedAt', '!updatedById', '!hasPhoto', '!hasPortraitPhoto', '!numPosts', '!numSubscribers', '!isSystem'], 'safe'];
	}

	/**
	 * Return channel url
	 * @param bool $scheme
	 * @return string
	 */
	public function getUrl($scheme = false)
	{
		return Url::toRoute(['/site/channel', 'item' => $this], $scheme);
	}

	/**
	 * @inheritdoc
	 */
	public function getPicBasePath($attributeName)
	{
		if(!$this->institutionId) return parent::getPicBasePath($attributeName);
		else return $this->institution->getPicBasePath('header');
	}

	/**
	 * @inheritdoc
	 */
	public function getPicBaseUrl($attributeName)
	{
		if(!$this->institutionId) return parent::getPicBaseUrl($attributeName);
		else return $this->institution->getPicBaseUrl('header');
	}
	
	/**
	 * Return image filename
	 * @param string $attributeName
	 * @param bool $forDisplay
	 * @return string
	 */
	public function getPicName($attributeName, $forDisplay = false)
	{
		if(!$this->institutionId) return parent::getPicName($attributeName, $forDisplay);
		else return $this->institution->getPicName('header', $forDisplay);
	}
}

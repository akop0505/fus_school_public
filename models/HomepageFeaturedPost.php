<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "HomepageFeaturedPost".
 */
class HomepageFeaturedPost extends base\HomepageFeaturedPost
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}

	/**
	 * @return array
	 */
	public function getChannelsForDropDown()
	{
		$getChannelsFromFeatured = FeaturedChannel::find()->asArray()->all();
		$channelId = ArrayHelper::getColumn($getChannelsFromFeatured, 'channelId');
		array_push($channelId, Channel::CHANNEL_HOME_LATEST, Channel::CHANNEL_HOME_MUST_SEE, Channel::CHANNEL_HOME_SLIDER, Channel::CHANNEL_HOME_BELOW_SLIDER);

		return $channelId;
	}
}

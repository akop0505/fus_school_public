<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as DiscoverChannelBaseActiveRecord;
use \app\models\Channel as DiscoverChannelChannel;

/**
 * This is the base-model class for table "DiscoverChannel".
 *
 * @property integer $channelId
 * @property integer $sort
 *
 * @property DiscoverChannelChannel $channel
 */
class DiscoverChannel extends DiscoverChannelBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'DiscoverChannel';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{DiscoverChannel} other{DiscoverChannels}}", ["n" =>  $n]);
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
			[['channelId', 'sort'], 'required'],
			[['channelId', 'sort'], 'integer'],
			[['channelId'], 'exist', 'skipOnError' => true, 'targetClass' => DiscoverChannelChannel::className(), 'targetAttribute' => ['channelId' => 'id']]
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
			'channelId' => Yii::t('models', 'Channel ID'),
			'sort' => Yii::t('models', 'Sort'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['channelId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'channel' => array('BELONGS_TO', DiscoverChannelChannel::className(), 'channelId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannel()
	{
		return $this->hasOne(DiscoverChannelChannel::className(), ['id' => 'channelId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\DiscoverChannel|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

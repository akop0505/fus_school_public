<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as FeaturedChannelBaseActiveRecord;
use \app\models\Channel as FeaturedChannelChannel;

/**
 * This is the base-model class for table "FeaturedChannel".
 *
 * @property integer $channelId
 * @property integer $sort
 * @property integer $numPost
 *
 * @property FeaturedChannelChannel $channel
 */
class FeaturedChannel extends FeaturedChannelBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'FeaturedChannel';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{FeaturedChannel} other{FeaturedChannels}}", ["n" =>  $n]);
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
			[['channelId', 'sort', 'numPost'], 'required'],
			[['channelId', 'sort', 'numPost'], 'integer'],
			[['channelId'], 'exist', 'skipOnError' => true, 'targetClass' => FeaturedChannelChannel::className(), 'targetAttribute' => ['channelId' => 'id']]
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
			'numPost' => Yii::t('models', 'Num Post'),
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
			'channel' => array('BELONGS_TO', FeaturedChannelChannel::className(), 'channelId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannel()
	{
		return $this->hasOne(FeaturedChannelChannel::className(), ['id' => 'channelId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\FeaturedChannel|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

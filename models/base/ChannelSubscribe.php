<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as ChannelSubscribeBaseActiveRecord;
use \app\models\Channel as ChannelSubscribeChannel;
use \app\models\User as ChannelSubscribeUser;

/**
 * This is the base-model class for table "ChannelSubscribe".
 *
 * @property integer $channelId
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property ChannelSubscribeChannel $channel
 * @property ChannelSubscribeUser $createdBy
 */
class ChannelSubscribe extends ChannelSubscribeBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'ChannelSubscribe';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{ChannelSubscribe} other{ChannelSubscribes}}", ["n" =>  $n]);
	}

	/**
	 * Return rule to mark attributes as unsafe or false if none
	 * @return bool|array
	 */
	protected function getUnsafeRule()
	{
		return [['!createdAt', '!createdById'], 'safe'];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = [
			[['channelId', 'createdAt', 'createdById'], 'required'],
			[['channelId', 'createdById'], 'integer'],
			[['createdAt'], 'safe'],
			[['channelId'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelSubscribeChannel::className(), 'targetAttribute' => ['channelId' => 'id']],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelSubscribeUser::className(), 'targetAttribute' => ['createdById' => 'id']]
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
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['channelId'] = false;
			$ret['createdById'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'channel' => array('BELONGS_TO', ChannelSubscribeChannel::className(), 'channelId'),
			'createdBy' => array('BELONGS_TO', ChannelSubscribeUser::className(), 'createdById'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannel()
	{
		return $this->hasOne(ChannelSubscribeChannel::className(), ['id' => 'channelId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(ChannelSubscribeUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\ChannelSubscribe|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

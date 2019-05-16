<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as PostChannelBaseActiveRecord;
use \app\models\Channel as PostChannelChannel;
use \app\models\User as PostChannelUser;
use \app\models\Post as PostChannelPost;

/**
 * This is the base-model class for table "PostChannel".
 *
 * @property integer $channelId
 * @property integer $postId
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property PostChannelChannel $channel
 * @property PostChannelUser $createdBy
 * @property PostChannelPost $post
 */
class PostChannel extends PostChannelBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PostChannel';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{PostChannel} other{PostChannels}}", ["n" =>  $n]);
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
			[['channelId', 'postId', 'createdAt', 'createdById'], 'required'],
			[['channelId', 'postId', 'createdById'], 'integer'],
			[['createdAt'], 'safe'],
			[['channelId'], 'exist', 'skipOnError' => true, 'targetClass' => PostChannelChannel::className(), 'targetAttribute' => ['channelId' => 'id']],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => PostChannelUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['postId'], 'exist', 'skipOnError' => true, 'targetClass' => PostChannelPost::className(), 'targetAttribute' => ['postId' => 'id']]
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
			'postId' => Yii::t('models', 'Post ID'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['channelId'] = false;
			$ret['createdById'] = false;
			$ret['postId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'channel' => array('BELONGS_TO', PostChannelChannel::className(), 'channelId'),
			'createdBy' => array('BELONGS_TO', PostChannelUser::className(), 'createdById'),
			'post' => array('BELONGS_TO', PostChannelPost::className(), 'postId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannel()
	{
		return $this->hasOne(PostChannelChannel::className(), ['id' => 'channelId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(PostChannelUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost()
	{
		return $this->hasOne(PostChannelPost::className(), ['id' => 'postId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\PostChannel|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

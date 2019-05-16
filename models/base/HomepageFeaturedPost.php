<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as HomepageFeaturedPostBaseActiveRecord;
use \app\models\Channel as HomepageFeaturedPostChannel;
use \app\models\Post as HomepageFeaturedPostPost;

/**
 * This is the base-model class for table "HomepageFeaturedPost".
 *
 * @property integer $channelId
 * @property integer $postId
 * @property integer $sort
 *
 * @property HomepageFeaturedPostChannel $channel
 * @property HomepageFeaturedPostPost $post
 */
class HomepageFeaturedPost extends HomepageFeaturedPostBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'HomepageFeaturedPost';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{HomepageFeaturedPost} other{HomepageFeaturedPosts}}", ["n" =>  $n]);
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
			[['channelId', 'postId', 'sort'], 'required'],
			[['channelId', 'postId', 'sort'], 'integer'],
			[['channelId'], 'exist', 'skipOnError' => true, 'targetClass' => HomepageFeaturedPostChannel::className(), 'targetAttribute' => ['channelId' => 'id']],
			[['postId'], 'exist', 'skipOnError' => true, 'targetClass' => HomepageFeaturedPostPost::className(), 'targetAttribute' => ['postId' => 'id']]
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
			'sort' => Yii::t('models', 'Sort'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['channelId'] = false;
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
			'channel' => array('BELONGS_TO', HomepageFeaturedPostChannel::className(), 'channelId'),
			'post' => array('BELONGS_TO', HomepageFeaturedPostPost::className(), 'postId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannel()
	{
		return $this->hasOne(HomepageFeaturedPostChannel::className(), ['id' => 'channelId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost()
	{
		return $this->hasOne(HomepageFeaturedPostPost::className(), ['id' => 'postId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\HomepageFeaturedPost|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

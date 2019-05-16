<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as ChannelBaseActiveRecord;
use \app\models\User as ChannelUser;
use \app\models\Institution as ChannelInstitution;
use \app\models\ChannelSubscribe as ChannelChannelSubscribe;
use \app\models\DiscoverChannel as ChannelDiscoverChannel;
use \app\models\FeaturedChannel as ChannelFeaturedChannel;
use \app\models\HomepageFeaturedPost as ChannelHomepageFeaturedPost;
use \app\models\Post as ChannelPost;
use \app\models\PostChannel as ChannelPostChannel;

/**
 * This is the base-model class for table "Channel".
 *
 * @property integer $id
 * @property integer $institutionId
 * @property integer $userId
 * @property string $name
 * @property string $description
 * @property integer $hasPhoto
 * @property integer $hasPortraitPhoto
 * @property integer $numPosts
 * @property integer $numSubscribers
 * @property integer $isActive
 * @property integer $isSystem
 * @property string $createdAt
 * @property integer $createdById
 * @property string $updatedAt
 * @property integer $updatedById
 *
 * @property ChannelUser $createdBy
 * @property ChannelInstitution $institution
 * @property ChannelUser $updatedBy
 * @property ChannelUser $user
 * @property ChannelChannelSubscribe[] $channelSubscribes
 * @property ChannelUser[] $createdBies
 * @property ChannelDiscoverChannel $discoverChannel
 * @property ChannelFeaturedChannel $featuredChannel
 * @property ChannelHomepageFeaturedPost[] $homepageFeaturedPosts
 * @property ChannelPost[] $posts
 * @property ChannelPostChannel[] $postChannels
 * @property ChannelPost[] $posts0
 */
class Channel extends ChannelBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Channel';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{Channel} other{Channels}}", ["n" =>  $n]);
	}

	/**
	 * Return rule to mark attributes as unsafe or false if none
	 * @return bool|array
	 */
	protected function getUnsafeRule()
	{
		return [['!createdAt', '!createdById', '!updatedAt', '!updatedById'], 'safe'];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = [
			[['institutionId', 'userId', 'hasPhoto', 'hasPortraitPhoto', 'numPosts', 'numSubscribers', 'isActive', 'isSystem', 'createdById', 'updatedById'], 'integer'],
			[['name', 'createdAt', 'createdById', 'updatedAt', 'updatedById'], 'required'],
			[['createdAt', 'updatedAt'], 'safe'],
			[['name', 'description'], 'string', 'max' => 255],
			[['name'], 'unique'],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['institutionId'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelInstitution::className(), 'targetAttribute' => ['institutionId' => 'id']],
			[['updatedById'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelUser::className(), 'targetAttribute' => ['updatedById' => 'id']],
			[['userId'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelUser::className(), 'targetAttribute' => ['userId' => 'id']]
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
			'id' => Yii::t('models', 'ID'),
			'institutionId' => Yii::t('models', 'Institution ID'),
			'userId' => Yii::t('models', 'User ID'),
			'name' => Yii::t('models', 'Name'),
			'description' => Yii::t('models', 'Description'),
			'hasPhoto' => Yii::t('models', 'Has Photo'),
			'hasPortraitPhoto' => Yii::t('models', 'Has Portrait Photo'),
			'numPosts' => Yii::t('models', 'Num Posts'),
			'numSubscribers' => Yii::t('models', 'Num Subscribers'),
			'isActive' => Yii::t('models', 'Is Active'),
			'isSystem' => Yii::t('models', 'Is System'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
			'updatedAt' => Yii::t('models', 'Updated At'),
			'updatedById' => Yii::t('models', 'Updated By ID'),
			'updatedBy' => Yii::t('models', 'Updated By'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['createdById'] = false;
			$ret['institutionId'] = false;
			$ret['updatedById'] = false;
			$ret['userId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'createdBy' => array('BELONGS_TO', ChannelUser::className(), 'createdById'),
			'institution' => array('BELONGS_TO', ChannelInstitution::className(), 'institutionId'),
			'updatedBy' => array('BELONGS_TO', ChannelUser::className(), 'updatedById'),
			'user' => array('BELONGS_TO', ChannelUser::className(), 'userId'),
			'channelSubscribes' => array('HAS_MANY', ChannelChannelSubscribe::className(), ''),
			'createdBies' => array('HAS_MANY', ChannelUser::className(), ''),
			'discoverChannel' => array('BELONGS_TO', ChannelDiscoverChannel::className(), ''),
			'featuredChannel' => array('BELONGS_TO', ChannelFeaturedChannel::className(), ''),
			'homepageFeaturedPosts' => array('HAS_MANY', ChannelHomepageFeaturedPost::className(), ''),
			'posts' => array('HAS_MANY', ChannelPost::className(), ''),
			'postChannels' => array('HAS_MANY', ChannelPostChannel::className(), ''),
			'posts0' => array('HAS_MANY', ChannelPost::className(), ''),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(ChannelUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitution()
	{
		return $this->hasOne(ChannelInstitution::className(), ['id' => 'institutionId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUpdatedBy()
	{
		return $this->hasOne(ChannelUser::className(), ['id' => 'updatedById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(ChannelUser::className(), ['id' => 'userId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannelSubscribes()
	{
		return $this->hasMany(ChannelChannelSubscribe::className(), ['channelId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBies()
	{
		return $this->hasMany(ChannelUser::className(), ['id' => 'createdById'])->viaTable('ChannelSubscribe', ['channelId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDiscoverChannel()
	{
		return $this->hasOne(ChannelDiscoverChannel::className(), ['channelId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFeaturedChannel()
	{
		return $this->hasOne(ChannelFeaturedChannel::className(), ['channelId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHomepageFeaturedPosts()
	{
		return $this->hasMany(ChannelHomepageFeaturedPost::className(), ['channelId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts()
	{
		return $this->hasMany(ChannelPost::className(), ['id' => 'postId'])->viaTable('HomepageFeaturedPost', ['channelId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostChannels()
	{
		return $this->hasMany(ChannelPostChannel::className(), ['channelId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts0()
	{
		return $this->hasMany(ChannelPost::className(), ['id' => 'postId'])->viaTable('PostChannel', ['channelId' => 'id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\Channel|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

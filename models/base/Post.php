<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as PostBaseActiveRecord;
use \app\models\HomepageFeaturedPost as PostHomepageFeaturedPost;
use \app\models\Channel as PostChannel;
use \app\models\User as PostUser;
use \app\models\PostChannel as PostPostChannel;
use \app\models\PostFavorite as PostPostFavorite;
use \app\models\PostFeatured as PostPostFeatured;
use \app\models\PostLater as PostPostLater;
use \app\models\PostLike as PostPostLike;
use \app\models\PostMedia as PostPostMedia;
use \app\models\PostRepost as PostPostRepost;
use \app\models\Institution as PostInstitution;
use \app\models\PostTag as PostPostTag;
use \app\models\Tag as PostTag;

/**
 * This is the base-model class for table "Post".
 *
 * @property integer $id
 * @property string $title
 * @property string $postText
 * @property integer $hasHeaderPhoto
 * @property integer $hasThumbPhoto
 * @property string $video
 * @property integer $views
 * @property integer $isActive
 * @property integer $isApproved
 * @property string $createdAt
 * @property integer $createdById
 * @property string $updatedAt
 * @property integer $updatedById
 * @property integer $approvedById
 * @property integer $isNational
 * @property string $datePublished
 * @property string $dateToBePublished
 * @property integer $dateToBePublishedSetById
 * @property string $fullTextContent
 *
 * @property PostHomepageFeaturedPost[] $homepageFeaturedPosts
 * @property PostChannel[] $channels
 * @property PostUser $approvedBy
 * @property PostUser $createdBy
 * @property PostUser $dateToBePublishedSetBy
 * @property PostUser $updatedBy
 * @property PostPostChannel[] $postChannels
 * @property PostChannel[] $channels0
 * @property PostPostFavorite[] $postFavorites
 * @property PostUser[] $createdBies
 * @property PostPostFeatured[] $postFeatureds
 * @property PostPostLater[] $postLaters
 * @property PostUser[] $createdBies0
 * @property PostPostLike[] $postLikes
 * @property PostUser[] $createdBies1
 * @property PostPostMedia[] $postMedia
 * @property PostPostRepost[] $postReposts
 * @property PostInstitution[] $institutions
 * @property PostPostTag[] $postTags
 * @property PostTag[] $tags
 */
class Post extends PostBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Post';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{Post} other{Posts}}", ["n" =>  $n]);
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
			[['title', 'postText', 'createdAt', 'createdById', 'updatedAt', 'updatedById'], 'required'],
			[['postText', 'fullTextContent'], 'string'],
			[['hasHeaderPhoto', 'hasThumbPhoto', 'views', 'isActive', 'isApproved', 'createdById', 'updatedById', 'approvedById', 'isNational', 'dateToBePublishedSetById'], 'integer'],
			[['createdAt', 'updatedAt', 'datePublished', 'dateToBePublished'], 'safe'],
			[['title', 'video'], 'string', 'max' => 255],
			[['approvedById'], 'exist', 'skipOnError' => true, 'targetClass' => PostUser::className(), 'targetAttribute' => ['approvedById' => 'id']],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => PostUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['dateToBePublishedSetById'], 'exist', 'skipOnError' => true, 'targetClass' => PostUser::className(), 'targetAttribute' => ['dateToBePublishedSetById' => 'id']],
			[['updatedById'], 'exist', 'skipOnError' => true, 'targetClass' => PostUser::className(), 'targetAttribute' => ['updatedById' => 'id']]
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
			'title' => Yii::t('models', 'Title'),
			'postText' => Yii::t('models', 'Post Text'),
			'hasHeaderPhoto' => Yii::t('models', 'Has Header Photo'),
			'hasThumbPhoto' => Yii::t('models', 'Has Thumb Photo'),
			'video' => Yii::t('models', 'Video'),
			'views' => Yii::t('models', 'Views'),
			'isActive' => Yii::t('models', 'Is Active'),
			'isApproved' => Yii::t('models', 'Is Approved'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
			'updatedAt' => Yii::t('models', 'Updated At'),
			'updatedById' => Yii::t('models', 'Updated By ID'),
			'updatedBy' => Yii::t('models', 'Updated By'),
			'approvedById' => Yii::t('models', 'Approved By ID'),
			'isNational' => Yii::t('models', 'Is National'),
			'datePublished' => Yii::t('models', 'Date Published'),
			'dateToBePublished' => Yii::t('models', 'Date To Be Published'),
			'dateToBePublishedSetById' => Yii::t('models', 'Date To Be Published Set By ID'),
			'fullTextContent' => Yii::t('models', 'Full Text Content'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['approvedById'] = false;
			$ret['createdById'] = false;
			$ret['dateToBePublishedSetById'] = false;
			$ret['updatedById'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'homepageFeaturedPosts' => array('HAS_MANY', PostHomepageFeaturedPost::className(), ''),
			'channels' => array('HAS_MANY', PostChannel::className(), ''),
			'approvedBy' => array('BELONGS_TO', PostUser::className(), 'approvedById'),
			'createdBy' => array('BELONGS_TO', PostUser::className(), 'createdById'),
			'dateToBePublishedSetBy' => array('BELONGS_TO', PostUser::className(), 'dateToBePublishedSetById'),
			'updatedBy' => array('BELONGS_TO', PostUser::className(), 'updatedById'),
			'postChannels' => array('HAS_MANY', PostPostChannel::className(), ''),
			'channels0' => array('HAS_MANY', PostChannel::className(), ''),
			'postFavorites' => array('HAS_MANY', PostPostFavorite::className(), ''),
			'createdBies' => array('HAS_MANY', PostUser::className(), ''),
			'postFeatureds' => array('HAS_MANY', PostPostFeatured::className(), ''),
			'postLaters' => array('HAS_MANY', PostPostLater::className(), ''),
			'createdBies0' => array('HAS_MANY', PostUser::className(), ''),
			'postLikes' => array('HAS_MANY', PostPostLike::className(), ''),
			'createdBies1' => array('HAS_MANY', PostUser::className(), ''),
			'postMedia' => array('HAS_MANY', PostPostMedia::className(), ''),
			'postReposts' => array('HAS_MANY', PostPostRepost::className(), ''),
			'institutions' => array('HAS_MANY', PostInstitution::className(), ''),
			'postTags' => array('HAS_MANY', PostPostTag::className(), ''),
			'tags' => array('HAS_MANY', PostTag::className(), ''),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHomepageFeaturedPosts()
	{
		return $this->hasMany(PostHomepageFeaturedPost::className(), ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels()
	{
		return $this->hasMany(PostChannel::className(), ['id' => 'channelId'])->viaTable('PostChannel', ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getApprovedBy()
	{
		return $this->hasOne(PostUser::className(), ['id' => 'approvedById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(PostUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDateToBePublishedSetBy()
	{
		return $this->hasOne(PostUser::className(), ['id' => 'dateToBePublishedSetById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUpdatedBy()
	{
		return $this->hasOne(PostUser::className(), ['id' => 'updatedById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostChannels()
	{
		return $this->hasMany(PostPostChannel::className(), ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels0()
	{
		return $this->hasMany(PostChannel::className(), ['id' => 'channelId'])->viaTable('PostChannel', ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostFavorites()
	{
		return $this->hasMany(PostPostFavorite::className(), ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBies()
	{
		return $this->hasMany(PostUser::className(), ['id' => 'createdById'])->viaTable('PostFavorite', ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostFeatureds()
	{
		return $this->hasMany(PostPostFeatured::className(), ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostLaters()
	{
		return $this->hasMany(PostPostLater::className(), ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBies0()
	{
		return $this->hasMany(PostUser::className(), ['id' => 'createdById'])->viaTable('PostLater', ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostLikes()
	{
		return $this->hasMany(PostPostLike::className(), ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBies1()
	{
		return $this->hasMany(PostUser::className(), ['id' => 'createdById'])->viaTable('PostLike', ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostMedia()
	{
		return $this->hasMany(PostPostMedia::className(), ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostReposts()
	{
		return $this->hasMany(PostPostRepost::className(), ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitutions()
	{
		return $this->hasMany(PostInstitution::className(), ['id' => 'institutionId'])->viaTable('PostRepost', ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostTags()
	{
		return $this->hasMany(PostPostTag::className(), ['postId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags()
	{
		return $this->hasMany(PostTag::className(), ['id' => 'tagId'])->viaTable('PostTag', ['postId' => 'id']);
	}
}

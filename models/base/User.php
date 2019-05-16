<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as UserBaseActiveRecord;
use \app\models\Channel as UserChannel;
use \app\models\ChannelSubscribe as UserChannelSubscribe;
use \app\models\Content as UserContent;
use \app\models\FileUpload as UserFileUpload;
use \app\models\Institution as UserInstitution;
use \app\models\InstitutionLike as UserInstitutionLike;
use \app\models\Post as UserPost;
use \app\models\PostChannel as UserPostChannel;
use \app\models\PostFavorite as UserPostFavorite;
use \app\models\PostLater as UserPostLater;
use \app\models\PostLike as UserPostLike;
use \app\models\PostRepost as UserPostRepost;
use \app\models\PostTag as UserPostTag;
use \app\models\StudentsFeatured as UserStudentsFeatured;
use \app\models\StudentsArchived as UserStudentsArchived;
use \app\models\TagSubscribe as UserTagSubscribe;
use \app\models\Tag as UserTag;
use \app\models\TimeZone as UserTimeZone;
use \app\models\UserActivity as UserUserActivity;
use \app\models\UserViews as UserUserViews;

/**
 * This is the base-model class for table "User".
 *
 * @property integer $id
 * @property string $username
 * @property string $authKey
 * @property string $passwordHash
 * @property string $passwordResetToken
 * @property string $email
 * @property integer $emailVerified
 * @property string $status
 * @property string $createdAt
 * @property string $updatedAt
 * @property string $lastLogin
 * @property string $firstName
 * @property string $lastName
 * @property integer $isMale
 * @property string $dateOfBirth
 * @property integer $hasPhoto
 * @property string $mobilePhone
 * @property integer $timeZoneId
 * @property integer $institutionId
 * @property string $about
 * @property string $avatar_name
 *
 * @property UserChannel[] $channels
 * @property UserChannel[] $channels0
 * @property UserChannel[] $channels1
 * @property UserChannelSubscribe[] $channelSubscribes
 * @property UserChannel[] $channels2
 * @property UserContent[] $contents
 * @property UserContent[] $contents0
 * @property UserFileUpload[] $fileUploads
 * @property UserInstitution[] $institutions
 * @property UserInstitution[] $institutions0
 * @property UserInstitutionLike[] $institutionLikes
 * @property UserInstitution[] $institutions1
 * @property UserPost[] $posts
 * @property UserPost[] $posts0
 * @property UserPost[] $posts1
 * @property UserPostChannel[] $postChannels
 * @property UserPostFavorite[] $postFavorites
 * @property UserPost[] $posts2
 * @property UserPostLater[] $postLaters
 * @property UserPost[] $posts3
 * @property UserPostLike[] $postLikes
 * @property UserPost[] $posts4
 * @property UserPostRepost[] $postReposts
 * @property UserPostTag[] $postTags
 * @property UserStudentsFeatured[] $studentsFeatureds
 * @property UserStudentsArchivded[] $studentsArchivdeds
 * @property UserTagSubscribe[] $tagSubscribes
 * @property UserTag[] $tags
 * @property UserInstitution $institution
 * @property UserTimeZone $timeZone
 * @property UserUserActivity[] $userActivities
 * @property UserUserViews[] $userViews
 */
class User extends UserBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'User';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{User} other{Users}}", ["n" =>  $n]);
	}

	/**
	 * Return rule to mark attributes as unsafe or false if none
	 * @return bool|array
	 */
	protected function getUnsafeRule()
	{
		return [['!createdAt', '!updatedAt'], 'safe'];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = [
			[['username', 'authKey', 'passwordHash', 'email', 'createdAt', 'updatedAt', 'timeZoneId'], 'required'],
			[['emailVerified', 'isMale', 'hasPhoto', 'timeZoneId', 'institutionId'], 'integer'],
			[['status', 'about'], 'string'],
			[['createdAt', 'updatedAt', 'lastLogin', 'dateOfBirth'], 'safe'],
			[['username', 'email', 'mobilePhone', 'avatar_name'], 'string', 'max' => 255],
			[['authKey'], 'string', 'max' => 32],
			[['passwordHash', 'passwordResetToken'], 'string', 'max' => 128],
			[['firstName', 'lastName'], 'string', 'max' => 64],
			[['username'], 'unique'],
			[['email'], 'unique'],
			[['institutionId'], 'exist', 'skipOnError' => true, 'targetClass' => UserInstitution::className(), 'targetAttribute' => ['institutionId' => 'id']],
			[['timeZoneId'], 'exist', 'skipOnError' => true, 'targetClass' => UserTimeZone::className(), 'targetAttribute' => ['timeZoneId' => 'id']],
			['status', 'in', 'range' => [
					static::STATUS_PENDING,
					static::STATUS_ACTIVE,
					static::STATUS_DELETED,
					static::STATUS_ARCHIVED
				]
			]
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
			'username' => Yii::t('models', 'Username'),
			'authKey' => Yii::t('models', 'Auth Key'),
			'passwordHash' => Yii::t('models', 'Password Hash'),
			'passwordResetToken' => Yii::t('models', 'Password Reset Token'),
			'email' => Yii::t('models', 'Email'),
			'emailVerified' => Yii::t('models', 'Email Verified'),
			'status' => Yii::t('models', 'Status'),
			'createdAt' => Yii::t('models', 'Created At'),
			'updatedAt' => Yii::t('models', 'Updated At'),
			'lastLogin' => Yii::t('models', 'Last Login'),
			'firstName' => Yii::t('models', 'First Name'),
			'lastName' => Yii::t('models', 'Last Name'),
			'isMale' => Yii::t('models', 'Is Male'),
			'dateOfBirth' => Yii::t('models', 'Date Of Birth'),
			'hasPhoto' => Yii::t('models', 'Has Photo'),
			'mobilePhone' => Yii::t('models', 'Mobile Phone'),
			'timeZoneId' => Yii::t('models', 'Time Zone ID'),
			'institutionId' => Yii::t('models', 'Institution ID'),
			'about' => Yii::t('models', 'About'),
			'avatar_name' => Yii::t('models', 'Avatar file name')
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['institutionId'] = false;
			$ret['timeZoneId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'channels' => array('HAS_MANY', UserChannel::className(), ''),
			'channels0' => array('HAS_MANY', UserChannel::className(), ''),
			'channels1' => array('HAS_MANY', UserChannel::className(), ''),
			'channelSubscribes' => array('HAS_MANY', UserChannelSubscribe::className(), ''),
			'channels2' => array('HAS_MANY', UserChannel::className(), ''),
			'contents' => array('HAS_MANY', UserContent::className(), ''),
			'contents0' => array('HAS_MANY', UserContent::className(), ''),
			'fileUploads' => array('HAS_MANY', UserFileUpload::className(), ''),
			'institutions' => array('HAS_MANY', UserInstitution::className(), ''),
			'institutions0' => array('HAS_MANY', UserInstitution::className(), ''),
			'institutionLikes' => array('HAS_MANY', UserInstitutionLike::className(), ''),
			'institutions1' => array('HAS_MANY', UserInstitution::className(), ''),
			'posts' => array('HAS_MANY', UserPost::className(), ''),
			'posts0' => array('HAS_MANY', UserPost::className(), ''),
			'posts1' => array('HAS_MANY', UserPost::className(), ''),
			'postChannels' => array('HAS_MANY', UserPostChannel::className(), ''),
			'postFavorites' => array('HAS_MANY', UserPostFavorite::className(), ''),
			'posts2' => array('HAS_MANY', UserPost::className(), ''),
			'postLaters' => array('HAS_MANY', UserPostLater::className(), ''),
			'posts3' => array('HAS_MANY', UserPost::className(), ''),
			'postLikes' => array('HAS_MANY', UserPostLike::className(), ''),
			'posts4' => array('HAS_MANY', UserPost::className(), ''),
			'postReposts' => array('HAS_MANY', UserPostRepost::className(), ''),
			'postTags' => array('HAS_MANY', UserPostTag::className(), ''),
			'studentsFeatureds' => array('HAS_MANY', UserStudentsFeatured::className(), ''),
			'studentsArchiveds' => array('HAS_MANY', UserStudentsArchived::className(), ''),
			'tagSubscribes' => array('HAS_MANY', UserTagSubscribe::className(), ''),
			'tags' => array('HAS_MANY', UserTag::className(), ''),
			'institution' => array('BELONGS_TO', UserInstitution::className(), 'institutionId'),
			'timeZone' => array('BELONGS_TO', UserTimeZone::className(), 'timeZoneId'),
			'userActivities' => array('HAS_MANY', UserUserActivity::className(), ''),
			'userViews' => array('HAS_MANY', UserUserViews::className(), ''),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels()
	{
		return $this->hasMany(UserChannel::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels0()
	{
		return $this->hasMany(UserChannel::className(), ['updatedById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels1()
	{
		return $this->hasMany(UserChannel::className(), ['userId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannelSubscribes()
	{
		return $this->hasMany(UserChannelSubscribe::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels2()
	{
		return $this->hasMany(UserChannel::className(), ['id' => 'channelId'])->viaTable('ChannelSubscribe', ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getContents()
	{
		return $this->hasMany(UserContent::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getContents0()
	{
		return $this->hasMany(UserContent::className(), ['updatedById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFileUploads()
	{
		return $this->hasMany(UserFileUpload::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitutions()
	{
		return $this->hasMany(UserInstitution::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitutions0()
	{
		return $this->hasMany(UserInstitution::className(), ['updatedById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitutionLikes()
	{
		return $this->hasMany(UserInstitutionLike::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitutions1()
	{
		return $this->hasMany(UserInstitution::className(), ['id' => 'institutionId'])->viaTable('InstitutionLike', ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts()
	{
		return $this->hasMany(UserPost::className(), ['approvedById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts0()
	{
		return $this->hasMany(UserPost::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts1()
	{
		return $this->hasMany(UserPost::className(), ['updatedById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostChannels()
	{
		return $this->hasMany(UserPostChannel::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostFavorites()
	{
		return $this->hasMany(UserPostFavorite::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts2()
	{
		return $this->hasMany(UserPost::className(), ['id' => 'postId'])->viaTable('PostFavorite', ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostLaters()
	{
		return $this->hasMany(UserPostLater::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts3()
	{
		return $this->hasMany(UserPost::className(), ['id' => 'postId'])->viaTable('PostLater', ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostLikes()
	{
		return $this->hasMany(UserPostLike::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts4()
	{
		return $this->hasMany(UserPost::className(), ['id' => 'postId'])->viaTable('PostLike', ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostReposts()
	{
		return $this->hasMany(UserPostRepost::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostTags()
	{
		return $this->hasMany(UserPostTag::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStudentsFeatureds()
	{
		return $this->hasMany(UserStudentsFeatured::className(), ['userId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStudentsArchiveds()
	{
		return $this->hasMany(UserStudentsArchived::className(), ['userId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTagSubscribes()
	{
		return $this->hasMany(UserTagSubscribe::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags()
	{
		return $this->hasMany(UserTag::className(), ['id' => 'tagId'])->viaTable('TagSubscribe', ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitution()
	{
		return $this->hasOne(UserInstitution::className(), ['id' => 'institutionId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTimeZone()
	{
		return $this->hasOne(UserTimeZone::className(), ['id' => 'timeZoneId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUserActivities()
	{
		return $this->hasMany(UserUserActivity::className(), ['createdById' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUserViews()
	{
		return $this->hasMany(UserUserViews::className(), ['createdById' => 'id']);
	}
}

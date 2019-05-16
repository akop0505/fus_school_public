<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as InstitutionBaseActiveRecord;
use \app\models\Channel as InstitutionChannel;
use \app\models\City as InstitutionCity;
use \app\models\User as InstitutionUser;
use \app\models\InstitutionLike as InstitutionInstitutionLike;
use \app\models\PostFeatured as InstitutionPostFeatured;
use \app\models\PostRepost as InstitutionPostRepost;
use \app\models\Post as InstitutionPost;
use \app\models\StudentsFeatured as InstitutionStudentsFeatured;
use \app\models\StudentsArchived as InstitutionStudentsArchived;

/**
 * This is the base-model class for table "Institution".
 *
 * @property integer $id
 * @property string $name
 * @property integer $cityId
 * @property string $address
 * @property string $themeColor
 * @property string $aboutUsLinkColor
 * @property integer $numLikes
 * @property integer $isActive
 * @property string $createdAt
 * @property integer $createdById
 * @property string $updatedAt
 * @property integer $updatedById
 * @property string $about
 * @property integer $hasLatestPhoto
 * @property string $latestLink
 * @property string $fbPageId
 * @property string $fbPageToken
 * @property string $fbAppId
 * @property string $fbAppSecret
 *
 * @property InstitutionChannel[] $channels
 * @property InstitutionCity $city
 * @property InstitutionUser $createdBy
 * @property InstitutionUser $updatedBy
 * @property InstitutionInstitutionLike[] $institutionLikes
 * @property InstitutionUser[] $createdBies
 * @property InstitutionPostFeatured[] $postFeatureds
 * @property InstitutionPostRepost[] $postReposts
 * @property InstitutionPost[] $posts
 * @property InstitutionStudentsFeatured[] $studentsFeatureds
 * @property InstitutionStudentsArchived[] $studentsArchiveds
 * @property InstitutionUser[] $users
 */
class Institution extends InstitutionBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Institution';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{Institution} other{Institutions}}", ["n" =>  $n]);
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
			[['name', 'cityId', 'address', 'createdAt', 'createdById', 'updatedAt', 'updatedById'], 'required'],
			[['cityId', 'numLikes', 'isActive', 'createdById', 'updatedById', 'hasLatestPhoto'], 'integer'],
			[['createdAt', 'updatedAt'], 'safe'],
			[['about'], 'string'],
			[['name', 'address', 'latestLink', 'fbPageToken', 'fbAppSecret'], 'string', 'max' => 255],
			[['themeColor', 'aboutUsLinkColor'], 'string', 'max' => 7],
			[['fbPageId', 'fbAppId'], 'string', 'max' => 30],
			[['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => InstitutionCity::className(), 'targetAttribute' => ['cityId' => 'id']],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => InstitutionUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['updatedById'], 'exist', 'skipOnError' => true, 'targetClass' => InstitutionUser::className(), 'targetAttribute' => ['updatedById' => 'id']]
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
			'name' => Yii::t('models', 'Name'),
			'cityId' => Yii::t('models', 'City ID'),
			'address' => Yii::t('models', 'Address'),
			'themeColor' => Yii::t('models', 'Theme Color'),
			'aboutUsLinkColor' => Yii::t('models', 'About Us Link Color'),
			'numLikes' => Yii::t('models', 'Num Likes'),
			'isActive' => Yii::t('models', 'Is Active'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
			'updatedAt' => Yii::t('models', 'Updated At'),
			'updatedById' => Yii::t('models', 'Updated By ID'),
			'updatedBy' => Yii::t('models', 'Updated By'),
			'about' => Yii::t('models', 'About'),
			'hasLatestPhoto' => Yii::t('models', 'Has Latest Photo'),
			'latestLink' => Yii::t('models', 'Latest Link'),
			'fbPageId' => Yii::t('models', 'Fb Page ID'),
			'fbPageToken' => Yii::t('models', 'Fb Page Token'),
			'fbAppId' => Yii::t('models', 'Fb App ID'),
			'fbAppSecret' => Yii::t('models', 'Fb App Secret'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['cityId'] = false;
			$ret['createdById'] = false;
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
			'channels' => array('HAS_MANY', InstitutionChannel::className(), ''),
			'city' => array('BELONGS_TO', InstitutionCity::className(), 'cityId'),
			'createdBy' => array('BELONGS_TO', InstitutionUser::className(), 'createdById'),
			'updatedBy' => array('BELONGS_TO', InstitutionUser::className(), 'updatedById'),
			'institutionLikes' => array('HAS_MANY', InstitutionInstitutionLike::className(), ''),
			'createdBies' => array('HAS_MANY', InstitutionUser::className(), ''),
			'postFeatureds' => array('HAS_MANY', InstitutionPostFeatured::className(), ''),
			'postReposts' => array('HAS_MANY', InstitutionPostRepost::className(), ''),
			'posts' => array('HAS_MANY', InstitutionPost::className(), ''),
			'studentsFeatureds' => array('HAS_MANY', InstitutionStudentsFeatured::className(), ''),
			'studentsArchiveds' => array('HAS_MANY', InstitutionStudentsArchived::className(), ''),
			'users' => array('HAS_MANY', InstitutionUser::className(), ''),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels()
	{
		return $this->hasMany(InstitutionChannel::className(), ['institutionId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCity()
	{
		return $this->hasOne(InstitutionCity::className(), ['id' => 'cityId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(InstitutionUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUpdatedBy()
	{
		return $this->hasOne(InstitutionUser::className(), ['id' => 'updatedById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitutionLikes()
	{
		return $this->hasMany(InstitutionInstitutionLike::className(), ['institutionId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBies()
	{
		return $this->hasMany(InstitutionUser::className(), ['id' => 'createdById'])->viaTable('InstitutionLike', ['institutionId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostFeatureds()
	{
		return $this->hasMany(InstitutionPostFeatured::className(), ['institutionId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostReposts()
	{
		return $this->hasMany(InstitutionPostRepost::className(), ['institutionId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts()
	{
		return $this->hasMany(InstitutionPost::className(), ['id' => 'postId'])->viaTable('PostRepost', ['institutionId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStudentsFeatureds()
	{
		return $this->hasMany(InstitutionStudentsFeatured::className(), ['institutionId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStudentsArchiveds()
	{
		return $this->hasMany(InstitutionStudentsArchived::className(), ['institutionId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers()
	{
		return $this->hasMany(InstitutionUser::className(), ['institutionId' => 'id']);
	}
}

<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as PostRepostBaseActiveRecord;
use \app\models\User as PostRepostUser;
use \app\models\Institution as PostRepostInstitution;
use \app\models\Post as PostRepostPost;

/**
 * This is the base-model class for table "PostRepost".
 *
 * @property integer $postId
 * @property integer $institutionId
 * @property integer $isApproved
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property PostRepostUser $createdBy
 * @property PostRepostInstitution $institution
 * @property PostRepostPost $post
 */
class PostRepost extends PostRepostBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PostRepost';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{PostRepost} other{PostReposts}}", ["n" =>  $n]);
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
			[['postId', 'institutionId', 'createdAt', 'createdById'], 'required'],
			[['postId', 'institutionId', 'isApproved', 'createdById'], 'integer'],
			[['createdAt'], 'safe'],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => PostRepostUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['institutionId'], 'exist', 'skipOnError' => true, 'targetClass' => PostRepostInstitution::className(), 'targetAttribute' => ['institutionId' => 'id']],
			[['postId'], 'exist', 'skipOnError' => true, 'targetClass' => PostRepostPost::className(), 'targetAttribute' => ['postId' => 'id']]
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
			'postId' => Yii::t('models', 'Post ID'),
			'institutionId' => Yii::t('models', 'Institution ID'),
			'isApproved' => Yii::t('models', 'Is Approved'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['createdById'] = false;
			$ret['institutionId'] = false;
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
			'createdBy' => array('BELONGS_TO', PostRepostUser::className(), 'createdById'),
			'institution' => array('BELONGS_TO', PostRepostInstitution::className(), 'institutionId'),
			'post' => array('BELONGS_TO', PostRepostPost::className(), 'postId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(PostRepostUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitution()
	{
		return $this->hasOne(PostRepostInstitution::className(), ['id' => 'institutionId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost()
	{
		return $this->hasOne(PostRepostPost::className(), ['id' => 'postId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\PostRepost|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

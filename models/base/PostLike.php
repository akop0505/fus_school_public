<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as PostLikeBaseActiveRecord;
use \app\models\User as PostLikeUser;
use \app\models\Post as PostLikePost;

/**
 * This is the base-model class for table "PostLike".
 *
 * @property integer $postId
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property PostLikeUser $createdBy
 * @property PostLikePost $post
 */
class PostLike extends PostLikeBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PostLike';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{PostLike} other{PostLikes}}", ["n" =>  $n]);
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
			[['postId', 'createdAt', 'createdById'], 'required'],
			[['postId', 'createdById'], 'integer'],
			[['createdAt'], 'safe'],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => PostLikeUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['postId'], 'exist', 'skipOnError' => true, 'targetClass' => PostLikePost::className(), 'targetAttribute' => ['postId' => 'id']]
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
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
		];
		if($this->getScenario() == 'formModel')
		{
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
			'createdBy' => array('BELONGS_TO', PostLikeUser::className(), 'createdById'),
			'post' => array('BELONGS_TO', PostLikePost::className(), 'postId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(PostLikeUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost()
	{
		return $this->hasOne(PostLikePost::className(), ['id' => 'postId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\PostLike|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as PostLaterBaseActiveRecord;
use \app\models\User as PostLaterUser;
use \app\models\Post as PostLaterPost;

/**
 * This is the base-model class for table "PostLater".
 *
 * @property integer $postId
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property PostLaterUser $createdBy
 * @property PostLaterPost $post
 */
class PostLater extends PostLaterBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PostLater';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{PostLater} other{PostLaters}}", ["n" =>  $n]);
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
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => PostLaterUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['postId'], 'exist', 'skipOnError' => true, 'targetClass' => PostLaterPost::className(), 'targetAttribute' => ['postId' => 'id']]
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
			'createdBy' => array('BELONGS_TO', PostLaterUser::className(), 'createdById'),
			'post' => array('BELONGS_TO', PostLaterPost::className(), 'postId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(PostLaterUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost()
	{
		return $this->hasOne(PostLaterPost::className(), ['id' => 'postId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\PostLater|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

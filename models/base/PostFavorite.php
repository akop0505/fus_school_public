<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as PostFavoriteBaseActiveRecord;
use \app\models\User as PostFavoriteUser;
use \app\models\Post as PostFavoritePost;

/**
 * This is the base-model class for table "PostFavorite".
 *
 * @property integer $postId
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property PostFavoriteUser $createdBy
 * @property PostFavoritePost $post
 */
class PostFavorite extends PostFavoriteBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PostFavorite';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{PostFavorite} other{PostFavorites}}", ["n" =>  $n]);
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
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => PostFavoriteUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['postId'], 'exist', 'skipOnError' => true, 'targetClass' => PostFavoritePost::className(), 'targetAttribute' => ['postId' => 'id']]
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
			'createdBy' => array('BELONGS_TO', PostFavoriteUser::className(), 'createdById'),
			'post' => array('BELONGS_TO', PostFavoritePost::className(), 'postId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(PostFavoriteUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost()
	{
		return $this->hasOne(PostFavoritePost::className(), ['id' => 'postId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\PostFavorite|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

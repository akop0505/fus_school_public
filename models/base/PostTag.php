<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as PostTagBaseActiveRecord;
use \app\models\User as PostTagUser;
use \app\models\Post as PostTagPost;
use \app\models\Tag as PostTagTag;

/**
 * This is the base-model class for table "PostTag".
 *
 * @property integer $tagId
 * @property integer $postId
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property PostTagUser $createdBy
 * @property PostTagPost $post
 * @property PostTagTag $tag
 */
class PostTag extends PostTagBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PostTag';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{PostTag} other{PostTags}}", ["n" =>  $n]);
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
			[['tagId', 'postId', 'createdAt', 'createdById'], 'required'],
			[['tagId', 'postId', 'createdById'], 'integer'],
			[['createdAt'], 'safe'],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => PostTagUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['postId'], 'exist', 'skipOnError' => true, 'targetClass' => PostTagPost::className(), 'targetAttribute' => ['postId' => 'id']],
			[['tagId'], 'exist', 'skipOnError' => true, 'targetClass' => PostTagTag::className(), 'targetAttribute' => ['tagId' => 'id']]
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
			'tagId' => Yii::t('models', 'Tag ID'),
			'postId' => Yii::t('models', 'Post ID'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['createdById'] = false;
			$ret['postId'] = false;
			$ret['tagId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'createdBy' => array('BELONGS_TO', PostTagUser::className(), 'createdById'),
			'post' => array('BELONGS_TO', PostTagPost::className(), 'postId'),
			'tag' => array('BELONGS_TO', PostTagTag::className(), 'tagId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(PostTagUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost()
	{
		return $this->hasOne(PostTagPost::className(), ['id' => 'postId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTag()
	{
		return $this->hasOne(PostTagTag::className(), ['id' => 'tagId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\PostTag|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}
